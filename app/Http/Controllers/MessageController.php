<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function inbox(Request $request): View
    {
        $user = auth()->user();
        $schoolId = $user->school_id;

        $query = Message::with(['sender'])
            ->where(function ($q) use ($user, $schoolId) {
                $q->where('recipient_id', $user->id)
                    ->orWhere(function ($q2) use ($user, $schoolId) {
                        $q2->where('recipient_role', $user->role === 'school_admin' ? 'teachers' : $user->role.'s')
                            ->where('school_id', $schoolId);
                    });
            });

        if ($user->role === 'super_admin') {
            $query = Message::with(['sender'])
                ->where(function ($q) {
                    $q->where('recipient_id', auth()->id());
                });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('subject', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        return view('messages.inbox', compact('messages'));
    }

    public function sent(Request $request): View
    {
        $query = Message::with(['recipient'])
            ->where('sender_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('subject', 'like', "%{$search}%");
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        return view('messages.sent', compact('messages'));
    }

    public function create(Request $request): View
    {
        $user = auth()->user();
        $schoolId = $user->school_id;

        $recipients = User::where('school_id', $schoolId)
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        $selectedRecipient = $request->input('recipient_id');
        $selectedRole = $request->input('recipient_role');

        return view('messages.create', compact('recipients', 'selectedRecipient', 'selectedRole'));
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $schoolId = $user->school_id;

        DB::beginTransaction();

        try {
            $data = [
                'school_id' => $schoolId,
                'sender_id' => $user->id,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'unread',
            ];

            if ($request->input('recipient_type') === 'direct') {
                $recipient = User::where('id', $request->recipient_id)
                    ->where('school_id', $schoolId)
                    ->firstOrFail();

                $data['recipient_id'] = $recipient->id;

                $message = Message::create($data);

                MessageRecipient::create([
                    'message_id' => $message->id,
                    'user_id' => $recipient->id,
                    'status' => 'unread',
                ]);
            } else {
                $roleMap = [
                    'teachers' => 'teacher',
                    'students' => 'student',
                    'parents' => 'parent',
                ];

                $data['recipient_role'] = $request->recipient_role;

                $message = Message::create($data);

                $recipients = User::where('school_id', $schoolId)
                    ->where('role', $roleMap[$request->recipient_role])
                    ->where('id', '!=', $user->id)
                    ->get();

                $recipientRecords = $recipients->map(fn ($r) => [
                    'message_id' => $message->id,
                    'user_id' => $r->id,
                    'status' => 'unread',
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();

                MessageRecipient::insert($recipientRecords);
            }

            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('messages', 'public');
                $message->update(['attachment' => $path]);
            }

            DB::commit();

            return redirect()->route('messages.sent')
                ->with('success', 'Message sent successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to send message. Please try again.');
        }
    }

    public function show(Message $message): View
    {
        $this->authorizeMessage($message);

        $user = auth()->user();

        if ($message->recipient_id == $user->id || $message->sender_id == $user->id) {
            if ($message->recipient_id == $user->id && $message->status === 'unread') {
                $message->update(['status' => 'read']);
            }

            if ($message->isBroadcast()) {
                $recipientRecord = MessageRecipient::where('message_id', $message->id)
                    ->where('user_id', $user->id)
                    ->first();

                if ($recipientRecord && $recipientRecord->status === 'unread') {
                    $recipientRecord->update([
                        'status' => 'read',
                        'read_at' => now(),
                    ]);
                }
            }
        }

        $message->load(['sender', 'recipient']);

        return view('messages.show', compact('message'));
    }

    public function destroy(Message $message): RedirectResponse
    {
        $user = auth()->user();

        abort_unless(
            $message->sender_id == $user->id || $message->recipient_id == $user->id,
            403,
            'You do not have permission to delete this message.'
        );

        if ($message->attachment) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return redirect()->back()
            ->with('success', 'Message deleted successfully.');
    }

    protected function authorizeMessage(Message $message): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($message->school_id !== $user->school_id, 403, 'Unauthorized access.');

        $isSender = $message->sender_id == $user->id;
        $isDirectRecipient = $message->recipient_id == $user->id;
        $isBroadcastRecipient = false;

        if ($message->isBroadcast()) {
            $roleMap = [
                'school_admin' => ['teachers', 'students', 'parents'],
                'teacher' => ['teachers', 'students', 'parents'],
                'student' => ['students'],
                'parent' => ['parents'],
            ];

            $allowedRoles = $roleMap[$user->role] ?? [];
            $isBroadcastRecipient = in_array($message->recipient_role, $allowedRoles);
        }

        abort_unless($isSender || $isDirectRecipient || $isBroadcastRecipient, 403, 'You do not have access to this message.');
    }
}

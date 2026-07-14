<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $schoolId = auth()->user()->school_id;
        $role = auth()->user()->role;

        $query = Announcement::with(['creator'])
            ->where('school_id', $schoolId);

        if (in_array($role, ['teacher', 'student', 'parent'])) {
            $query->where('status', 'published')
                ->where(function ($q) use ($role) {
                    $q->where('audience', 'everyone')
                        ->orWhere('audience', $role.'s');
                })
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('audience')) {
            $query->where('audience', $request->audience);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $announcements = $query->latest()->paginate(10)->withQueryString();

        $canManage = in_array($role, ['super_admin', 'school_admin']);

        return view('announcements.index', compact('announcements', 'canManage'));
    }

    public function create(): View
    {
        return view('announcements.create');
    }

    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $schoolId = auth()->user()->school_id;

        $data = $request->validated();
        $data['school_id'] = $schoolId;
        $data['user_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement): View
    {
        $this->authorizeAnnouncement($announcement);

        $announcement->load(['creator', 'school']);

        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement): View
    {
        $this->authorizeManage($announcement);

        return view('announcements.edit', compact('announcement'));
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $this->authorizeManage($announcement);

        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $this->authorizeManage($announcement);

        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    protected function authorizeAnnouncement(Announcement $announcement): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($announcement->school_id !== $user->school_id, 403, 'Unauthorized access.');

        if (in_array($user->role, ['teacher', 'student', 'parent'])) {
            $isValidAudience = $announcement->audience === 'everyone'
                || $announcement->audience === $user->role.'s';

            $isExpired = $announcement->expires_at && $announcement->expires_at->isPast();

            abort_if(
                $announcement->status !== 'published' || ! $isValidAudience || $isExpired,
                403,
                'You do not have access to this announcement.'
            );
        }
    }

    protected function authorizeManage(Announcement $announcement): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($announcement->school_id !== $user->school_id, 403, 'Unauthorized access.');

        abort_if(! in_array($user->role, ['super_admin', 'school_admin']), 403, 'You do not have permission to manage announcements.');
    }
}

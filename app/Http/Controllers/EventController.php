<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $schoolId = $user->school_id;

        $query = Event::with(['creator'])
            ->where('school_id', $schoolId);

        if (in_array($user->role, ['teacher', 'student', 'parent'])) {
            $query->where('status', 'published');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }

        $events = $query->latest('event_date')->paginate(15)->withQueryString();

        $canManage = in_array($user->role, ['super_admin', 'school_admin']);

        return view('events.index', compact('events', 'canManage'));
    }

    public function create(): View
    {
        return view('events.create');
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        Event::create([
            'school_id' => auth()->user()->school_id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'event_type' => $request->event_type,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'status' => $request->input('status', 'draft'),
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event): View
    {
        $this->authorizeEvent($event);

        $event->load(['creator', 'school']);

        return view('events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $this->authorizeManage($event);

        return view('events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $this->authorizeManage($event);

        $event->update($request->validated());

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeManage($event);

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    protected function authorizeEvent(Event $event): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($event->school_id !== $user->school_id, 403, 'Unauthorized access.');

        if (in_array($user->role, ['teacher', 'student', 'parent'])) {
            abort_if($event->status !== 'published', 403, 'You do not have access to this event.');
        }
    }

    protected function authorizeManage(Event $event): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($event->school_id !== $user->school_id, 403, 'Unauthorized access.');

        abort_if(! in_array($user->role, ['super_admin', 'school_admin']), 403, 'You do not have permission to manage events.');
    }
}

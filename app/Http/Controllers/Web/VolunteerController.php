<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VolunteerTask;
use App\Models\VolunteerRegistration;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    public function index()
    {
        $tasks = VolunteerTask::all();
        return view('volunteers.index', compact('tasks'));
    }

    public function create()
    {
        if(!auth()->user()->hasPermissionTo('manage_volunteers')) abort(401);
        return view('volunteers.create');
    }

    public function store(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_volunteers')) abort(401);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'task_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:task_date',
            'hours_required' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1'
        ]);

        $task = new VolunteerTask();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->task_date = $request->task_date;
        $task->end_date = $request->end_date;
        $task->hours_required = $request->hours_required;
        $task->capacity = $request->capacity;
        $task->save();

        return redirect()->route('volunteers_list')->with('success', 'Volunteer task created successfully.');
    }

    public function edit(VolunteerTask $task)
    {
        if(!auth()->user()->hasPermissionTo('manage_volunteers')) abort(401);
        return view('volunteers.edit', compact('task'));
    }

    public function update(Request $request, VolunteerTask $task)
    {
        if(!auth()->user()->hasPermissionTo('manage_volunteers')) abort(401);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'task_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:task_date',
            'hours_required' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1'
        ]);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->task_date = $request->task_date;
        $task->end_date = $request->end_date;
        $task->hours_required = $request->hours_required;
        $task->capacity = $request->capacity;
        $task->save();

        return redirect()->route('volunteers_list')->with('success', 'Volunteer task updated successfully.');
    }

    public function destroy(VolunteerTask $task)
    {
        if(!auth()->user()->hasPermissionTo('manage_volunteers')) abort(401);
        
        $task->delete();
        return redirect()->route('volunteers_list')->with('success', 'Volunteer task deleted successfully.');
    }

    public function register(Request $request, VolunteerTask $task)
    {
        $user = Auth::user();

        // 1. Check if Task is Finished
        if ($task->isFinished()) {
            return redirect()->back()->with('error', 'This volunteer opportunity has already ended.');
        }

        // 2. Check Capacity
        if ($task->isFull()) {
            return redirect()->back()->with('error', 'Sorry, this task has reached its maximum volunteer capacity.');
        }

        // 3. Check if user is already registered for this task
        $existingReg = VolunteerRegistration::where('user_id', $user->id)
                            ->where('volunteer_task_id', $task->id)
                            ->first();

        if ($existingReg) {
            return redirect()->back()->with('error', 'You are already registered for this task.');
        }

        // 4. Conflict Detection: Check if user is registered for another task on the same date
        $conflict = VolunteerRegistration::where('user_id', $user->id)
            ->whereHas('task', function($q) use ($task) {
                $q->whereDate('task_date', $task->task_date);
            })->first();

        if ($conflict) {
            return redirect()->back()->with('error', 'Schedule Conflict: You are already volunteering for another task on this date.');
        }

        // Register the user
        $registration = new VolunteerRegistration();
        $registration->user_id = $user->id;
        $registration->volunteer_task_id = $task->id;
        $registration->status = 'registered';
        $registration->save();

        return redirect()->route('volunteers_list')->with('success', 'You have successfully registered for the task!');
    }

    public function logHours(Request $request, VolunteerRegistration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'hours_logged' => 'required|numeric|min:0|max:' . $registration->task->hours_required,
        ]);

        $registration->hours_logged = $request->hours_logged;
        $registration->save();

        return redirect()->back()->with('success', 'Hours successfully logged.');
    }
}

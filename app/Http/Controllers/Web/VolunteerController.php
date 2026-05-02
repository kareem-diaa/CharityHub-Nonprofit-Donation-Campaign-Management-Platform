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
            'hours_required' => 'required|integer|min:1'
        ]);

        $task = new VolunteerTask();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->task_date = $request->task_date;
        $task->hours_required = $request->hours_required;
        $task->save();

        return redirect()->route('volunteers_list')->with('success', 'Volunteer task created successfully.');
    }

    public function register(Request $request, VolunteerTask $task)
    {
        $user = Auth::user();

        // Check if user is already registered for this task
        $existingReg = VolunteerRegistration::where('user_id', $user->id)
                            ->where('volunteer_task_id', $task->id)
                            ->first();

        if ($existingReg) {
            return redirect()->back()->with('error', 'You are already registered for this task.');
        }

        // Conflict Detection: Check if user is registered for another task on the same date
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
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\VolunteerTask;
use App\Models\VolunteerRegistration;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function impactReport(Request $request)
    {
        // Require admin/manager permission
        if(!auth()->user()->hasPermissionTo('manage_campaigns')) {
            abort(401, 'Unauthorized access.');
        }

        // 1. Total Donations amount
        $totalDonations = Donation::where('status', 'completed')->sum('amount');
        
        // 2. Total Campaigns
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();

        // 3. Volunteer Impact
        $totalVolunteers = VolunteerRegistration::count();
        $totalVolunteerHours = VolunteerTask::join('volunteer_registrations', 'volunteer_tasks.id', '=', 'volunteer_registrations.volunteer_task_id')
                                ->sum('volunteer_tasks.hours_required');

        // 4. Breakdown by Campaign
        $campaignBreakdown = Campaign::select('title', 'raised_amount', 'goal_amount')
                                ->orderBy('raised_amount', 'desc')
                                ->take(5)
                                ->get();

        return view('reports.impact', compact(
            'totalDonations', 
            'totalCampaigns', 
            'activeCampaigns', 
            'totalVolunteers', 
            'totalVolunteerHours',
            'campaignBreakdown'
        ));
    }

    public function allDonations()
    {
        if(!auth()->user()->hasPermissionTo('manage_campaigns')) abort(401);

        $donations = Donation::with(['user', 'campaign'])->latest()->get();
        return view('reports.donations', compact('donations'));
    }

    public function allVolunteers()
    {
        if(!auth()->user()->hasPermissionTo('manage_volunteers')) abort(401);

        $registrations = VolunteerRegistration::with(['user', 'task'])->latest()->get();
        return view('reports.volunteers', compact('registrations'));
    }

    public function removeRegistration(VolunteerRegistration $registration)
    {
        if(!auth()->user()->hasPermissionTo('manage_volunteers')) abort(401);

        $registration->delete();
        return redirect()->back()->with('success', 'Volunteer removed from task successfully.');
    }
}

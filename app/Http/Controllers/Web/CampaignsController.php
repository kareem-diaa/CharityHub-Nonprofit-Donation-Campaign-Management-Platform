<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Storage;

class CampaignsController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::all();
        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        // Check permission (Broken Access Control protection)
        if(!auth()->user()->hasPermissionTo('manage_campaigns')) {
            abort(401, 'Unauthorized access.');
        }

        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_campaigns')) abort(401);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:campaigns',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $campaign = new Campaign();
        $campaign->title = $request->title;
        $campaign->slug = $request->slug;
        $campaign->description = $request->description;
        $campaign->goal_amount = $request->goal_amount;
        $campaign->deadline = $request->deadline;
        $campaign->latitude = $request->latitude;
        $campaign->longitude = $request->longitude;

        if ($request->hasFile('image')) {
            $campaign->image = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->save();

        return redirect()->route('campaigns_list')->with('success', 'Campaign created successfully.');
    }

    public function edit(Request $request, Campaign $campaign)
    {
        if(!auth()->user()->hasPermissionTo('manage_campaigns')) abort(401);

        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        if(!auth()->user()->hasPermissionTo('manage_campaigns')) abort(401);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'status' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $campaign->title = $request->title;
        $campaign->description = $request->description;
        $campaign->goal_amount = $request->goal_amount;
        $campaign->deadline = $request->deadline;
        $campaign->status = $request->status;
        $campaign->latitude = $request->latitude;
        $campaign->longitude = $request->longitude;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
                Storage::disk('public')->delete($campaign->image);
            }
            $campaign->image = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->save();

        return redirect()->route('campaigns_list')->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        if(!auth()->user()->hasPermissionTo('manage_campaigns')) abort(401);

        $campaign->delete();
        return redirect()->route('campaigns_list')->with('success', 'Campaign deleted successfully.');
    }

    public function show(Campaign $campaign)
    {
        return view('campaigns.show', compact('campaign'));
    }
}

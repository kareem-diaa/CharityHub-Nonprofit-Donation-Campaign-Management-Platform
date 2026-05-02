<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Campaign;

class CampaignsController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return response()->json([
            'status' => 'success',
            'data' => $campaigns
        ]);
    }

    public function show($id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['status' => 'error', 'message' => 'Campaign not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $campaign
        ]);
    }
}

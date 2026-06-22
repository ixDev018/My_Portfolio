<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function track(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'project_id' => 'nullable|integer|exists:projects,id',
            'meta_data' => 'nullable|array'
        ]);

        Interaction::create([
            'type' => $validated['type'],
            'project_id' => $validated['project_id'] ?? null,
            'meta_data' => $validated['meta_data'] ?? null,
            'ip_address' => $request->ip() ? hash('sha256', $request->ip()) : null,
            'user_agent' => $request->userAgent()
        ]);

        return response()->json(['status' => 'success']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::orderByDesc('created_at')->orderByDesc('id')->take(100)->get();

        return view('activity-logs.index', compact('logs'));
    }

    public function show(string $id)
    {
        $log = ActivityLog::findOrFail($id);

        return view('activity-logs.show', compact('log'));
    }
}

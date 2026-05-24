<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\ApiCrashLog;
use App\Models\ApiActivityLog;
use App\Http\Controllers\Controller;

class ApiLogController extends Controller
{

    public function activity(Request $request)
    {
        $logs = ApiActivityLog::query()

            ->when($request->search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('url', 'like', "%{$search}%")
                        ->orWhere('method', 'like', "%{$search}%")
                        ->orWhere('status_code', 'like', "%{$search}%");
                });
            })

            ->when($request->status, function ($query, $status) {

                $query->where('status_code', $status);
            })

            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'pages.api-logs.activity',
            compact('logs')
        );
    }

    public function crashes(Request $request)
    {
        $logs = ApiCrashLog::query()

            ->when($request->search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('message', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'pages.api-logs.crashes',
            compact('logs')
        );
    }

    public function showCrash(ApiCrashLog $log)
    {
        return view(
            'pages.api-logs.show-crash',
            compact('log')
        );
    }
}
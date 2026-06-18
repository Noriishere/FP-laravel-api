<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ApiActivityLog;
use App\Models\ApiCrashLog;
use Illuminate\Http\Request;

class ApiLogController extends Controller
{
    public function activity(Request $request)
    {
        $title = 'Apilog Activity || Admin Gassin!';
        $navtitle = 'Apilog';
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
            compact('logs', 'navtitle', 'title')
        );
    }

    public function crashes(Request $request)
    {
        $title = 'Crash Log Activity || Admin Gassin!';
        $navtitle = 'Crash Log';
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
            compact('logs', 'navtitle', 'title')
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

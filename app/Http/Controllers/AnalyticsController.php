<?php

namespace App\Http\Controllers;

use App\Models\CommandLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function heatmap()
    {
        $days = 365;
        $start = Carbon::now()->subDays($days);
        $logs = CommandLog::where('timestamp', '>=', $start)
            ->selectRaw('DATE(timestamp) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $data = [];
        for ($i = 0; $i <= $days; $i++) {
            $date = Carbon::now()->subDays($days - $i)->toDateString();
            $data[] = ['date' => $date, 'count' => $logs[$date]->count ?? 0];
        }
        return response()->json($data);
    }

    public function hourlyStats()
    {
        $stats = CommandLog::selectRaw('EXTRACT(HOUR FROM timestamp) as hour, COUNT(*) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        return response()->json($stats);
    }

    public function topUsers(Request $request)
    {
        $limit = $request->get('limit', 10);
        $top = CommandLog::selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
        return response()->json($top);
    }
}
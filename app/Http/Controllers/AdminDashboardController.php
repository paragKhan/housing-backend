<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\HousingModel;
use App\Models\Message;
use App\Models\Subdivision;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function getOverview(Request $request){
        $data = [];

        $data['total_users'] = User::all()->count();
        $data['total_applications'] = Application::all()->count();
        $data['total_subdivisions'] = Subdivision::all()->count();
        $data['total_housing_models'] = HousingModel::all()->count();

        return response()->json($data);
    }

    public function getUserJoiningStats()
    {
        $data = [];

        $data["yearly"] = DB::table('users')->select(
            DB::raw("COUNT(*) as count"),
            DB::raw("DATE_FORMAT(created_at, '%Y') as label"),
        )->groupBy('label')
            ->get();

        $data["monthly"] = DB::table('users')->select(
            DB::raw("COUNT(*) as count"),
            DB::raw("MONTHNAME(created_at) as label"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
        )->whereYear('created_at', date('Y'))
            ->groupBy('month', "label")
            ->get();

        $data["weekly"] = DB::table('users')->select(
            DB::raw("COUNT(*) as count"),
            DB::raw("DAYNAME(created_at) as label"),
            DB::raw("DATE_FORMAT(created_at, '%m-%d') as day"),
        )->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->groupBy('day', "label")
            ->get();

        $data["daily"] = DB::table('users')->select(
            DB::raw("COUNT(*) as count"),
            DB::raw("DATE_FORMAT(created_at, '%H:00') as label"),
        )->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', date('d'))
            ->groupBy('label')
            ->get();

        return response()->json($data);
    }

    public function getMessageStats()
    {
        $data = [];

        $data['all'] = DB::table('messages')
            ->select(
                DB::raw("COUNT(*) as count"),
                DB::raw('status as st')
            )
            ->groupBy('st')
            ->get();

        $data['last30'] = DB::table('messages')
            ->select(
                DB::raw("COUNT(*) as count"),
                DB::raw('status as st')
            )
            ->whereDate('created_at', '>=', now()->subDays(30)->startOfDay())
            ->whereDate('created_at', '<=', now()->endOfDay())
            ->groupBy('st')
            ->get();

        $data['last7'] = DB::table('messages')
            ->select(
                DB::raw("COUNT(*) as count"),
                DB::raw('status as st')
            )
            ->whereDate('created_at', '>=', now()->subDays(7)->startOfDay())
            ->whereDate('created_at', '<=', now()->endOfDay())
            ->groupBy('st')
            ->get();

        $data['today'] = DB::table('messages')
            ->select(
                DB::raw("COUNT(*) as count"),
                DB::raw('status as st')
            )
            ->whereDate('created_at', '>=', now()->startOfDay())
            ->whereDate('created_at', '<=', now()->endOfDay())
            ->groupBy('st')
            ->get();

        return response()->json($data);
    }

    public function getSubdivisionStats(){
        $data = DB::table('subdivisions')
            ->select(
                DB::raw('(COUNT(*)) as count'),
                DB::raw('location as location')
            )->groupBy('location')
            ->get();

        return response()->json($data);
    }
}

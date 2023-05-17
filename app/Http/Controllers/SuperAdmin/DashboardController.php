<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BotUser;
use App\Models\UserEngagement;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role_id', 2)->get();
        $bot_users = BotUser::all();
        $user_engagements = UserEngagement::all();
        $today_engagement = UserEngagement::whereDate('created_at', Carbon::today())->get('engage_count');
        if($request->ajax()){
            $data = User::select('id','name')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('superadmin.dashboard', compact('users', 'bot_users', 'user_engagements', 'today_engagement'));
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BotUser;
use App\Models\UserEngagement;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Excel;
use DB;

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

    public function download(Request $request)
    {
        // dd($request->input('viber_portal'));
        // dd($request->input('start_date'));
        if($request->input('viber_portal') == 'Select Portal' ){
            return redirect()->route('dashboard')->with('message', 'Please choose viber portal!');
        }
        switch ($request->input('viber_portal')) {
            case 'portal_one':
                $db =  \DB::connection('mysql_portal_one');
                $this->downloadExcel($request->input('start_date'), $request->input('end_date'));
                break;
            case 'portal_two':
                $db =  \DB::connection('mysql_portal_two');
                break;
            case 'roche':
                $db = \DB::connection('mysql_roche');
                break;
            case 'yoma':
                $db =  \DB::connection('mysql_yoma');
                break;
            default:
                echo "";
        }
        $query = "
                SELECT
                com.name AS `Company Name`,
                sum(case when vc.delivery_status = 0 then 1 else 0 end) as Delivered ,
                sum(case when vc.delivery_status = 1 then 1 else 0 end) as Seen,
                sum(CASE WHEN vc.delivery_status = 2 OR vc.status = 'Error' OR vc.status = 'null' THEN '1' ELSE '0' END) as `Error`
                from viber_contacts vc inner join viber_lists vl on vl.id = vc.viber_list_id 
                INNER JOIN companies com ON com.id = 1 
                where vl.company_id = 1 and
                date(vc.delivery_date) between $request->input('start_date') and $request->input('end_date');
                ";
        $raw_query = DB::select(DB::raw($query));

        $data = json_decode(json_encode($raw_query), true);
        Log::debug("DATA". $data);
            
        return Excel::create('viber_report', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
    }

    private function downloadExcel(Request $request){
        //
    }
}

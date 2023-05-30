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
// use Excel;
use DB;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
// use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

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
        // if($request->input('viber_portal' != 'Select Portal') && $request->input('start_date') != '' && $request->input('end_date')){
            switch ($request->input('viber_portal')) {
                case 'portal_one':
                    // $db = \DB::connection('mysql_roche');
                    $db = \DB::connection('mysql_portal_one');
                    $database = $db->getDatabaseName();
                    // dd($database);

                    $host = '167.99.64.17';
                    $username = 'root';
                    $privateKeyPath = config('ssh.pem_key');
                    $mysql_username = config('ssh.mysql_username');
                    // dd($mysql_username);
                    $mysql_password = config('ssh.mysql_password');

                    $privateKey = PublicKeyLoader::load($privateKeyPath);

                    $ssh = new SSH2($host);
                    if (!$ssh->login($username, $privateKey)) {
                        exit('Login Failed');
                    }

                    // Log::debug($ssh->exec('uptime'));


                    $start_date = $request->input('start_date');
                    $end_date = $request->input('end_date');

                    
                    $final_array = [52,64,72];
                    $result_array = [];
                    $array_length = count($final_array);
                    // Log::debug($final_array[0]);
                    for ($i = 0; $i < $array_length; $i++){
                        $get_data = 'MYSQL_PWD=3pY9n5J1emGqBFKgLtwv mysql -u '. $mysql_username . ' -e "USE '.$database.'; CALL viber_report(\''.$start_date.'\', \''.$end_date.'\',\''.$final_array[$i].'\')"';
                        $datas = $ssh->exec($get_data);
                        // Log::debug($datas. 'Row =>'. $company_id);

                        $array = explode("\t", $datas);
                        $string = $array[3];
                        $parenthesisPosition = strpos($string, ')');
                        $trimmedString = trim(substr($string, $parenthesisPosition + 1));
                        $data = [
                            [
                                $trimmedString,
                                $array[4],
                                $array[5],
                                $array[6]
                            ]
                        ];
                        array_push($result_array, $data);
                    }
                    return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'procedure_data.xlsx');
                    $result = json_decode(json_encode($result_array), true);
                    return Excel::create('viber_report', function($excel) use ($result) {
                        $excel->sheet('mySheet', function($sheet) use ($result)
                        {
                            $sheet->fromArray($result);
                        });
                    })->download('xlsx');
                    
                    break;
                case 'uu':
                    \DB::connection('mysql_yoma');
                    break;
                default:
                    echo "";
            }
        // }
    }

    private function downloadExcel(Request $request){
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
}

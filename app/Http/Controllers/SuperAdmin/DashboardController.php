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
        if($request->input('viber_portal') == 'Select Portal' ){
            return redirect()->route('dashboard')->with('message', 'Please choose viber portal!');
        }
        if($request->input('start_date') == '' && $request->input('start_date') == ''){
            return redirect()->route('dashboard')->with('message', 'Please choose date range!');
        }
        switch ($request->input('viber_portal')) {
            case 'portal_one':
                $db = \DB::connection('mysql_portal_one');
                $database = $db->getDatabaseName();
                $host = config('ssh.portal_one_host');
                $username = config('ssh.username');
                $privateKeyPath = config('ssh.pem_key');
                $mysql_username = config('ssh.portal_one_username');
                $privateKey = PublicKeyLoader::load($privateKeyPath);

                $ssh = new SSH2($host);
                if (!$ssh->login($username, $privateKey)) {
                    exit('Login Failed');
                }
                // Log::debug($ssh->exec('uptime'));
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');

                $company_list_command = 'MYSQL_PWD=3pY9n5J1emGqBFKgLtwv mysql -u '. $mysql_username . ' -e "USE '.$database.'; CALL company_list()"';
                $company_result = $ssh->exec($company_list_command);

                $cleanedList = str_replace("id_list", "", trim($company_result));
                $array = explode(",", $cleanedList);
                $cleaned_array = array_map('trim', $array);
                $company_array = array_map('intval', $cleaned_array);
                $result_array = [];

                for ($i = 0; $i < count($company_array); $i++){
                    // Log::debug($company_array[$i]);
                    $get_data = 'MYSQL_PWD=3pY9n5J1emGqBFKgLtwv mysql -u '. $mysql_username . ' -e "USE '.$database.'; CALL viber_report(\''.$start_date.'\', \''.$end_date.'\',\''.$company_array[$i].'\')"';
                    $datas = $ssh->exec($get_data);
                    // Log::debug($datas. 'Row =>'. $company_array[$i]);
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
                return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'portal_one_viber_report('.$start_date.'_'.$end_date.').xlsx');
                $result = json_decode(json_encode($result_array), true);
                return Excel::create('viber_report', function($excel) use ($result) {
                    $excel->sheet('mySheet', function($sheet) use ($result)
                    {
                        $sheet->fromArray($result);
                    });
                })->download('xlsx');
            break;
            case 'portal_two':
                $db = \DB::connection('mysql_portal_two');
                $database = $db->getDatabaseName();
                // dd($database);
                $host = config('ssh.portal_two_host');
                $portal_two_host = '127.0.0.1';
                $username = config('ssh.username');
                $privateKeyPath = config('ssh.pem_key');
                $mysql_username = config('ssh.portal_two_username');
                $privateKey = PublicKeyLoader::load($privateKeyPath);

                $ssh = new SSH2($host);
                if (!$ssh->login($username, $privateKey)) {
                    exit('Login Failed');
                }
                // Log::debug($ssh->exec('uptime'));
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');

                $company_list_command = 'MYSQL_PWD=BpbS8Tc38.s/-p>E+ mysql -h '. $portal_two_host . ' -u '. $mysql_username . ' -e "USE '.$database.'; CALL company_list()"';
                // $company_list_command = 'MYSQL_PWD=BpbS8Tc38.s/-p>E+ mysql -u '. $mysql_username . ' -e "USE '.$database.'; CALL company_list()"';
                $company_result = $ssh->exec($company_list_command);

                $cleanedList = str_replace("id_list", "", trim($company_result));
                $array = explode(",", $cleanedList);
                $cleaned_array = array_map('trim', $array);
                $company_array = array_map('intval', $cleaned_array);
                $result_array = [];

                for ($i = 0; $i < count($company_array); $i++){
                    // Log::debug($company_array[$i]);
                    // $get_data = 'MYSQL_PWD=BpbS8Tc38.s/-p>E+ mysql -u '. $mysql_username . ' -e "USE '.$database.'; CALL viber_report(\''.$start_date.'\', \''.$end_date.'\',\''.$company_array[$i].'\')"';
                    $get_data = 'MYSQL_PWD=BpbS8Tc38.s/-p>E+ mysql -h '. $portal_two_host . ' -u '. $mysql_username . ' -e "USE '.$database.'; CALL viber_report(\''.$start_date.'\', \''.$end_date.'\',\''.$company_array[$i].'\')"';
                    Log::debug($get_data);
                    $datas = $ssh->exec($get_data);
                    // Log::debug($datas. 'Row =>'. $company_array[$i]);
                    $array = explode("\t", $datas);
                    Log::debug($array);
                    // $string = $array[3];
                    // $parenthesisPosition = strpos($string, ')');
                    // $trimmedString = trim(substr($string, $parenthesisPosition + 1));
                    // $data = [
                    //     [
                    //         $trimmedString,
                    //         $array[4],
                    //         $array[5],
                    //         $array[6]
                    //     ]
                    // ];
                    // array_push($result_array, $data);
                }
                // return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'portal_two_viber_report('.$start_date.'_'.$end_date.').xlsx');
                // $result = json_decode(json_encode($result_array), true);
                // return Excel::create('viber_report', function($excel) use ($result) {
                //     $excel->sheet('mySheet', function($sheet) use ($result)
                //     {
                //         $sheet->fromArray($result);
                //     });
                // })->download('xlsx');
            break;
            case 'roche':
                $db = \DB::connection('mysql_roche');
                $database = $db->getDatabaseName();
                $host = config('ssh.roche_host');
                $username = config('ssh.username');
                $privateKeyPath = config('ssh.pem_key');
                $mysql_username = config('ssh.roche_username');
                $privateKey = PublicKeyLoader::load($privateKeyPath);

                $ssh = new SSH2($host);
                if (!$ssh->login($username, $privateKey)) {
                    exit('Login Failed');
                }
                // Log::debug($ssh->exec('uptime'));
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $result_array = [];
                $get_data = 'MYSQL_PWD=6tNFgF190Roche mysql -u '. $mysql_username . ' -e "USE '.$database.'; CALL viber_report(\''.$start_date.'\', \''.$end_date.'\')"';
                $datas = $ssh->exec($get_data);
                // Log::debug($datas);
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

                return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'roche_viber_report('.$start_date.'_'.$end_date.').xlsx');
                $result = json_decode(json_encode($result_array), true);
                return Excel::create('viber_report', function($excel) use ($result) {
                    $excel->sheet('mySheet', function($sheet) use ($result)
                    {
                        $sheet->fromArray($result);
                    });
                })->download('xlsx');
            break;
            case 'yoma':
                $db = \DB::connection('mysql_yoma');
                $database = $db->getDatabaseName();
                $host = config('ssh.yoma_host');
                $yoma_host = config('ssh.ssh_yoma_host');
                $username = config('ssh.username');
                $privateKeyPath = config('ssh.pem_key');
                $mysql_username = config('ssh.yoma_username');
                $privateKey = PublicKeyLoader::load($privateKeyPath);

                $ssh = new SSH2($yoma_host);
                if (!$ssh->login($username, $privateKey)) {
                    exit('Login Failed');
                }
                // Log::debug($ssh->exec('uptime'));
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $result_array = [];
                $get_data = 'MYSQL_PWD=eD5%cN6)yW8<uD3%zR0%yV7:eV4:nC1{ mysql -h '. $host . ' -u '. $mysql_username . ' -e "USE '.$database.'; CALL viber_report(\''.$start_date.'\', \''.$end_date.'\')"';
                $datas = $ssh->exec($get_data);
                Log::debug($datas);
                // $array = explode("\t", $datas);
                // $string = $array[3];
                // $parenthesisPosition = strpos($string, ')');
                // $trimmedString = trim(substr($string, $parenthesisPosition + 1));
                // $data = [
                //     [
                //         $trimmedString,
                //         $array[4],
                //         $array[5],
                //         $array[6]
                //     ]
                // ];
                // array_push($result_array, $data);
                // return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'yoma_viber_report('.$start_date.'_'.$end_date.').xlsx');
                // $result = json_decode(json_encode($result_array), true);
                // return Excel::create('viber_report', function($excel) use ($result) {
                //     $excel->sheet('mySheet', function($sheet) use ($result)
                //     {
                //         $sheet->fromArray($result);
                //     });
                // })->download('xlsx');
            break;
            default:
                echo "default";
        }
    }
}

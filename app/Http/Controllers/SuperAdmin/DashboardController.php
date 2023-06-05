<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class DashboardController extends Controller
{
    public function index()
    {
        return view('superadmin.dashboard');
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
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $results = \DB::connection('mysql_portal_one')->select('CALL company_list()');
                $result_string = json_encode($results[0]->id_list);
                Log::debug($result_string);
                $filter_string = str_replace('"', '', $result_string);
                Log::debug($filter_string);
                $company_array = explode(',', $filter_string);
                $result_array = [];

                for ($i = 0; $i < count($company_array); $i++){
                    $arrayOfObjects = \DB::connection('mysql_portal_one')->select('CALL viber_report(?, ?, ?)', [$start_date, $end_date, $company_array[$i]]);
                    $mergedObject = new stdClass();
                    foreach ($arrayOfObjects as $object) {
                        $mergedObject = (object) array_merge((array) $mergedObject, (array) $object);
                    }
                    $company_name = $mergedObject->name;
                    $delivered = $mergedObject->Delivered;
                    $seen = $mergedObject->Seen;
                    $error = $mergedObject->Error;
                    $data = [
                        [
                            $company_name,
                            $delivered,
                            $seen,
                            $error
                        ]
                    ];
                    array_push($result_array, $data);
                }
                Log::debug($result_array);
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
                // $db = \DB::connection('mysql_portal_two');
                // $database = $db->getDatabaseName();
                // $host = config('ssh.portal_two_host');
                // $portal_two_host = '127.0.0.1';
                // $username = config('ssh.username');
                // $privateKeyPath = config('ssh.pem_key');
                // $mysql_username = config('ssh.portal_two_username');

                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');

                $results = \DB::connection('mysql_portal_two')->select('CALL company_list()');
                $result_string = json_encode($results[0]->id_list);
                $filter_string = str_replace('"', '', $result_string);
                $company_array = explode(',', $filter_string);
                $result_array = [];
                for ($i = 0; $i < count($company_array); $i++){
                    $arrayOfObjects = \DB::connection('mysql_portal_two')->select('CALL viber_report(?, ?, ?)', [$start_date, $end_date, $company_array[$i]]);
                    $mergedObject = new stdClass();
                    foreach ($arrayOfObjects as $object) {
                        $mergedObject = (object) array_merge((array) $mergedObject, (array) $object);
                    }
                    $company_name = $mergedObject->name;
                    $delivered = $mergedObject->Delivered;
                    $seen = $mergedObject->Seen;
                    $error = $mergedObject->Error;
                    $data = [
                        [
                            $company_name,
                            $delivered,
                            $seen,
                            $error
                        ]
                    ];
                    array_push($result_array, $data);
                }
                return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'portal_two_viber_report('.$start_date.'_'.$end_date.').xlsx');
                $result = json_decode(json_encode($result_array), true);
                return Excel::create('viber_report', function($excel) use ($result) {
                    $excel->sheet('mySheet', function($sheet) use ($result)
                    {
                        $sheet->fromArray($result);
                    });
                })->download('xlsx');
            break;
            case 'roche':
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $result_array = [];
                $arrayOfObjects = \DB::connection('mysql_roche')->select('CALL viber_report(?, ?)', [$start_date, $end_date]);
                $mergedObject = new stdClass();
                foreach ($arrayOfObjects as $object) {
                    $mergedObject = (object) array_merge((array) $mergedObject, (array) $object);
                }
                $company_name = $mergedObject->name;
                $delivered = $mergedObject->Delivered;
                $seen = $mergedObject->Seen;
                $error = $mergedObject->Error;
                $data = [
                    [
                        $company_name,
                        $delivered,
                        $seen,
                        $error
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
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $result_array = [];
                $arrayOfObjects = \DB::connection('mysql_yoma')->select('CALL viber_report(?, ?)', [$start_date, $end_date]);
                $mergedObject = new stdClass();
                foreach ($arrayOfObjects as $object) {
                    $mergedObject = (object) array_merge((array) $mergedObject, (array) $object);
                }
                $company_name = $mergedObject->name;
                $delivered = $mergedObject->Delivered;
                $seen = $mergedObject->Seen;
                $error = $mergedObject->Error;
                $data = [
                    [
                        $company_name,
                        $delivered,
                        $seen,
                        $error
                    ]
                ];
                array_push($result_array, $data);
                return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'yoma_viber_report('.$start_date.'_'.$end_date.').xlsx');
                $result = json_decode(json_encode($result_array), true);
                return Excel::create('viber_report', function($excel) use ($result) {
                    $excel->sheet('mySheet', function($sheet) use ($result)
                    {
                        $sheet->fromArray($result);
                    });
                })->download('xlsx');
            break;
            default:
                echo "default";
        }
    }
}

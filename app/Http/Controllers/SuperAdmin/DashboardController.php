<?php

namespace App\Http\Controllers\SuperAdmin;

use stdClass;
use Exception;
use phpseclib3\Net\SSH2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PortalOneCompanyList;
use Maatwebsite\Excel\Facades\Excel;
use phpseclib3\Crypt\PublicKeyLoader;
use PDO;
use PDOException;

class DashboardController extends Controller
{
    public function index()
    {
        return view('superadmin.dashboard');
    }

    public function download(Request $request)
    {
        if ($request->input('viber_portal') == 'Select Portal') {
            return redirect()->route('dashboard')->with('message', 'Please choose viber portal!');
        }
        if ($request->input('start_date') == '' && $request->input('start_date') == '') {
            return redirect()->route('dashboard')->with('message', 'Please choose date range!');
        }
        Log::debug("Test");
        switch ($request->input('viber_portal')) {
            case 'portal_one':

                // old code //
                // $start_date = $request->input('start_date');
                // $end_date = $request->input('end_date');
                // $company_array = [];
                // // dd($company_array);


                // // Retrieve company IDs using company_list procedure
                // $arrayOfObjects = DB::select('CALL company_list()');
                // foreach ($arrayOfObjects as $object) {
                //     $company_id = $object->id;
                //     array_push($company_array, $company_id);
                // }

                // $result_array = [];
                // foreach ($company_array as $companyId) {
                //     try {
                //         $arrayOfObjects = DB::select('CALL viber_report(?, ?, ?)', [$start_date, $end_date, $companyId]);
                //         foreach ($arrayOfObjects as $object) {
                //             $company_name = $object->name;
                //             $delivered = $object->Delivered;
                //             $seen = $object->Seen;
                //             $error = $object->Error;
                //             $data = [
                //                 $company_name,
                //                 $delivered,
                //                 $seen,
                //                 $error
                //             ];
                //             array_push($result_array, $data);
                //         }
                //     } catch (PDOException $e) {
                //         // Log or handle the database connection error
                //         Log::error('Database Connection Error: ' . $e->getMessage());
                //         // Return an error response to the user
                //         return response()->json(['error' => 'Failed to connect to the database'], 500);
                //     }
                // }

                // return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'portal_one_viber_report(' . $start_date . '_' . $end_date . ').xlsx');


                //new code//

                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $company_array = [];

                // Retrieve company IDs using company_list procedure
                try {
                    $host = env('DB_PORTAL_ONE_HOST');
                    $database = env('DB_PORTAL_ONE_DATABASE');
                    $username = env('DB_PORTAL_ONE_USERNAME');
                    $password = env('DB_PORTAL_ONE_PASSWORD');

                    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);

                    // Execute the company_list procedure
                    $stmt = $pdo->prepare('CALL company_list()');
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $row) {
                        $company_id = $row['id'];
                        array_push($company_array, $company_id);
                    }
                } catch (PDOException $e) {
                    // Log or handle the database connection error
                    Log::error('Database Connection Error: ' . $e->getMessage());
                    // Return an error response to the user
                    return response()->json(['error' => 'Failed to connect to the database'], 500);
                }

                $result_array = [];
                foreach ($company_array as $companyId) {
                    try {
                        $stmt = $pdo->prepare('CALL viber_report(?, ?, ?)');
                        $stmt->bindParam(1, $start_date, PDO::PARAM_STR);
                        $stmt->bindParam(2, $end_date, PDO::PARAM_STR);
                        $stmt->bindParam(3, $companyId, PDO::PARAM_INT);
                        $stmt->execute();
                        $arrayOfObjects = $stmt->fetchAll(PDO::FETCH_OBJ);

                        foreach ($arrayOfObjects as $object) {
                            $company_name = $object->name;
                            $delivered = $object->Delivered;
                            $seen = $object->Seen;
                            $error = $object->Error;
                            $data = [
                                $company_name,
                                $delivered,
                                $seen,
                                $error
                            ];
                            array_push($result_array, $data);
                        }
                    } catch (PDOException $e) {
                        // Log or handle the database connection error
                        Log::error('Database Connection Error: ' . $e->getMessage());
                        // Return an error response to the user
                        return response()->json(['error' => 'Failed to connect to the database'], 500);
                    }
                }

                Log::debug(json_decode(json_encode($result_array), true));

                return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'portal_one_viber_report(' . $start_date . '_' . $end_date . ').xlsx');



                break;
            case 'portal_two':

                // old code
                // $db = \DB::connection('mysql_portal_two');
                // $database = $db->getDatabaseName();
                // $host = config('ssh.portal_two_host');
                // $username = config('ssh.username');
                // $privateKeyPath = config('ssh.pem_key');
                // $mysql_username = config('ssh.portal_two_username');

                // $privateKey = PublicKeyLoader::load($privateKeyPath);
                // $db = \DB::connection('mysql_portal_two');
                // $database = $db->getDatabaseName();
                // $host = config('ssh.portal_two_host');
                // $username = config('ssh.username');
                // $privateKeyPath = config('ssh.pem_key');
                // $mysql_username = config('ssh.portal_two_username');

                // $privateKey = PublicKeyLoader::load($privateKeyPath);
                // $ssh = new SSH2($host);

                // if (!$ssh->login($username, $privateKey)) {
                //     exit('Login Failed');
                // }

                // $start_date = $request->input('start_date');
                // $end_date = $request->input('end_date');
                // $company_list_command = 'MYSQL_PWD="BpbS8Tc38.s/-p>E+" mysql -u ' . $mysql_username . ' -e "USE ' . $database . '; CALL company_list()"';
                // $company_result = $ssh->exec($company_list_command);
                // $cleanedList = str_replace("id_list", "", trim($company_result));
                // $array = explode(",", $cleanedList);
                // $cleaned_array = array_map('trim', $array);
                // $company_array = array_map('intval', $cleaned_array);
                // $result_array = [];

                // foreach ($company_array as $companyId) {
                //     $get_data = 'MYSQL_PWD="BpbS8Tc38.s/-p>E+" mysql -u ' . $mysql_username . ' -e "USE ' . $database . '; CALL viber_report(\'' . $start_date . '\', \'' . $end_date . '\',\'' . $companyId . '\')"';
                //     $datas = $ssh->exec($get_data);
                //     Log::debug('Received data: ' . $datas); // Log the received data

                //     $array = explode("\t", $datas);

                //     // Check if $array has enough elements
                //     if (count($array) >= 7) {
                //         $string = $array[3];
                //         $parenthesisPosition = strpos($string, ')');
                //         $trimmedString = trim(substr($string, $parenthesisPosition + 1));
                //         $data = [
                //             [
                //                 $trimmedString,
                //                 $array[4],
                //                 $array[5],
                //                 $array[6]
                //             ]
                //         ];
                //         array_push($result_array, $data);
                //     } else {
                //         // Log the error
                //         Log::error('Error: Insufficient data elements in $array on companyId ' . $companyId);
                //         // You can also include additional information in the log message if needed

                //         // Or you can throw an exception
                //         // throw new Exception('Error: Insufficient data elements in $array on companyId ' . $companyId);
                //     }
                // }

                // return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'portal_two_viber_report(' . $start_date . '_' . $end_date . ').xlsx');


                //new code

                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $company_array = [];

                // Retrieve company IDs using company_list procedure
                try {
                    $host = env('DB_PORTAL_TWO_HOST');
                    $database = env('DB_PORTAL_TWO_DATABASE');
                    $username = env('DB_PORTAL_TWO_USERNAME');
                    $password = env('DB_PORTAL_TWO_PASSWORD');

                    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);

                    // Execute the company_list procedure
                    $stmt = $pdo->prepare('CALL company_list()');
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $row) {
                        $company_id = $row['id'];
                        array_push($company_array, $company_id);
                    }
                } catch (PDOException $e) {
                    // Log or handle the database connection error
                    Log::error('Database Connection Error: ' . $e->getMessage());
                    // Return an error response to the user
                    return response()->json(['error' => 'Failed to connect to the database'], 500);
                }

                $result_array = [];
                foreach ($company_array as $companyId) {
                    try {
                        $stmt = $pdo->prepare('CALL viber_report(?, ?, ?)');
                        $stmt->bindParam(1, $start_date, PDO::PARAM_STR);
                        $stmt->bindParam(2, $end_date, PDO::PARAM_STR);
                        $stmt->bindParam(3, $companyId, PDO::PARAM_INT);
                        $stmt->execute();
                        $arrayOfObjects = $stmt->fetchAll(PDO::FETCH_OBJ);

                        foreach ($arrayOfObjects as $object) {
                            $company_name = $object->name;
                            $delivered = $object->Delivered;
                            $seen = $object->Seen;
                            $error = $object->Error;
                            $data = [
                                $company_name,
                                $delivered,
                                $seen,
                                $error
                            ];
                            array_push($result_array, $data);
                        }
                    } catch (PDOException $e) {
                        // Log or handle the database connection error
                        Log::error('Database Connection Error: ' . $e->getMessage());
                        // Return an error response to the user
                        return response()->json(['error' => 'Failed to connect to the database'], 500);
                    }
                }

                Log::debug(json_decode(json_encode($result_array), true));

                return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'portal_two_viber_report(' . $start_date . '_' . $end_date . ').xlsx');


                break;
            case 'roche':

                // OLD CODE //
                // $start_date = $request->input('start_date');
                // $end_date = $request->input('end_date');
                // $result_array = [];
                // $arrayOfObjects = \DB::connection('mysql_roche')->select('CALL viber_report(?, ?)', [$start_date, $end_date]);
                // $mergedObject = new stdClass();
                // foreach ($arrayOfObjects as $object) {
                //     $mergedObject = (object) array_merge((array) $mergedObject, (array) $object);
                // }
                // $company_name = $mergedObject->name;
                // $delivered = $mergedObject->Delivered;
                // $seen = $mergedObject->Seen;
                // $error = $mergedObject->Error;
                // $data = [
                //     [
                //         $company_name,
                //         $delivered,
                //         $seen,
                //         $error
                //     ]
                // ];
                // array_push($result_array, $data);
                // return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'roche_viber_report('.$start_date.'_'.$end_date.').xlsx');
                // $result = json_decode(json_encode($result_array), true);
                // return Excel::create('viber_report', function($excel) use ($result) {
                //     $excel->sheet('mySheet', function($sheet) use ($result)
                //     {
                //         $sheet->fromArray($result);
                //     });
                // })->download('xlsx');

                //new code//
                try {
                    $start_date = $request->input('start_date');
                    $end_date = $request->input('end_date');
                    $result_array = [];

                    $dsn = 'mysql:host=' . env('DB_ROCHE_HOST') . ';dbname=' . env('DB_ROCHE_DATABASE');
                    $username = env('DB_ROCHE_USERNAME');
                    $password = env('DB_ROCHE_PASSWORD');

                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
                    ];

                    $pdo = new PDO($dsn, $username, $password, $options);

                    $stmt = $pdo->prepare('CALL viber_report(?, ?)');
                    $stmt->execute([$start_date, $end_date]);
                    $arrayOfObjects = $stmt->fetchAll();

                    $mergedObject = new stdClass();

                    foreach ($arrayOfObjects as $object) {
                        $mergedObject = (object) array_merge((array) $mergedObject, (array) $object);
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
                    Log::debug(json_decode(json_encode($result_array), true));

                    return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'roche_viber_report(' . $start_date . '_' . $end_date . ').xlsx');
                } catch (PDOException $e) {
                    // Log or handle the database connection error
                    Log::error('Database Connection Error: ' . $e->getMessage());
                    // Return an error response to the user
                    return response()->json(['error' => 'Failed to connect to the database'], 500);
                }
                break;

            case 'yoma':

                // old code//
                // $start_date = $request->input('start_date');
                // $end_date = $request->input('end_date');
                // $result_array = [];
                // // Log::debug($result_array);
                // $arrayOfObjects = \DB::connection('mysql_yoma')->select('CALL viber_report(?, ?)', [$start_date, $end_date]);
                // Log::debug($arrayOfObjects);
                // $mergedObject = new stdClass();
                // foreach ($arrayOfObjects as $object) {
                //     $mergedObject = (object) array_merge((array) $mergedObject, (array) $object);
                // }
                // $company_name = $mergedObject->name;
                // $delivered = $mergedObject->Delivered;
                // $seen = $mergedObject->Seen;
                // $error = $mergedObject->Error;
                // $data = [
                //     [
                //         $company_name,
                //         $delivered,
                //         $seen,
                //         $error
                //     ]
                // ];
                // array_push($result_array, $data);
                // return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'yoma_viber_report(' . $start_date . '_' . $end_date . ').xlsx');
                // $result = json_decode(json_encode($result_array), true);
                // return Excel::create('viber_report', function ($excel) use ($result) {
                //     $excel->sheet('mySheet', function ($sheet) use ($result) {
                //         $sheet->fromArray($result);
                //     });
                // })->download('xlsx');

                //new code//
                //new code//
                try {
                    $start_date = $request->input('start_date');
                    $end_date = $request->input('end_date');
                    $result_array = [];

                    $dsn = 'mysql:host=' . env('DB_YOMA_HOST') . ';dbname=' . env('DB_YOMA_DATABASE');
                    $username = env('DB_YOMA_USERNAME');
                    $password = env('DB_YOMA_PASSWORD');

                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
                    ];

                    $pdo = new PDO($dsn, $username, $password, $options);

                    $stmt = $pdo->prepare('CALL viber_report(?, ?)');
                    $stmt->execute([$start_date, $end_date]);
                    $arrayOfObjects = $stmt->fetchAll();

                    $mergedObject = new stdClass();

                    foreach ($arrayOfObjects as $object) {
                        $mergedObject = (object) array_merge((array) $mergedObject, (array) $object);
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

                    Log::debug(json_decode(json_encode($result_array), true));

                    return Excel::download(new \App\Exports\ProcedureDataExport(json_decode(json_encode($result_array), true)), 'yoma_viber_report(' . $start_date . '_' . $end_date . ').xlsx');
                } catch (PDOException $e) {
                    // Log or handle the database connection error
                    Log::error('Database Connection Error: ' . $e->getMessage());
                    // Return an error response to the user
                    return response()->json(['error' => 'Failed to connect to the database'], 500);
                }

                break;
            default:
                echo "default";
        }
    }

    public function testConnection()
    {
        // $host = env('DB_PORTAL_TWO_HOST');
        // $database = env('DB_PORTAL_TWO_DATABASE');
        // $username = env('DB_PORTAL_TWO_USERNAME');
        // $password = env('DB_PORTAL_TWO_PASSWORD');

        // try {
        //     $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);

        //     // Execute the company_list procedure
        //     $stmt = $pdo->prepare('CALL company_list()');
        //     $stmt->execute();
        //     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     dd($result);

        //     if (count($result) > 0) {
        //         return 'Company list retrieved successfully!';
        //     } else {
        //         return 'No companies found.';
        //     }
        // } catch (PDOException $e) {
        //     return 'Failed to connect to the database: ' . $e->getMessage();
        // }


        try {
            $host = env('DB_PORTAL_TWO_HOST');
            $database = env('DB_PORTAL_TWO_DATABASE');
            $username = env('DB_PORTAL_TWO_USERNAME');
            $password = env('DB_PORTAL_TWO_PASSWORD');

            $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Test the connection by executing a simple query
            $stmt = $pdo->query('SELECT 1');
            $stmt->fetch();

            echo "Database connection is established.";
        } catch (PDOException $e) {
            // Log or handle the database connection error
            Log::error('Database Connection Error: ' . $e->getMessage());
            // Return an error response to the user
            return response()->json(['error' => 'Failed to connect to the database'], 500);
        }
    }
}

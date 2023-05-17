<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class GraphChartController extends Controller
{
    public function index()
    {
        $users = User::all();
        $names = [];
        foreach($users as $user){
            
            $name = $user['name'];
            array_push($names, $name);
        }
        
        return response()->json([
            'data' => $names
        ], 200);
    } 
}

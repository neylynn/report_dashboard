<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserSettingController extends Controller
{
    public function index(Request $request)
    {
        return view('superadmin.user-setting');
    }

    public function getAdminUsers(Request $request)
    {
        if ($request->ajax()) {

            $users = User::where('role_id', 2)->orderBy('name')->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function($users) {
                    return '<a href="users-edit/'.$users->id.'" class="remove btn btn-warning btn-sm mr-1"><i class="fas fa-edit"></i> Edit</a><a href="users-delete/'.$users->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>';
                })
                ->make(true);

        }
    }
}

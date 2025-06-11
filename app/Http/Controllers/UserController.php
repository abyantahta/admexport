<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*');
            return DataTables::of($data)
                ->addColumn('action', function($row){
                    $actionBtn = '<div class="flex space-x-2">
                        <a href="'.route('users.edit', $row->id).'" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form action="'.route('users.destroy', $row->id).'" method="POST" class="inline">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }
} 
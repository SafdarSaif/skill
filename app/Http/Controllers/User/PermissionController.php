<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\PermissionRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();
    if (Auth::check() && Auth::user()->hasPermissionTo('view permissions')) {
      if ($request->ajax()) {

        $data = Permission::orderBy('id', 'desc')->get();

        return Datatables::of($data)
          ->addIndexColumn()
          ->addColumn('roles', function ($data) {
            $roles = $this->getRolesByPermission($data->name);
            return $roles;
          })
          ->editColumn('created_at', function ($data) {
            $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
            return $formatedDate;
          })
          ->make(true);
      }
      return view('user.permission.index');
    } else {
      return response()->view('errors.403', [], 403);
    }
  }

  public function getRolesByPermission($permission)
  {
    $roles = Role::all();
    foreach ($roles as $role) {
      $data[] = $role->hasPermissionTo($permission) ? $role->name : '';
    }

    return array_filter($data);
  }

  public function create()
  {
    return view('user.permission.create');
  }

  public function store(PermissionRequest $request)
  {
    try {
      $permission = Permission::make(["name" => $request->name]);
      $permission->saveOrFail();
      return response()->json([
        'status' => 'success',
        'message' => $request->name . ' created successfully',
      ]);
    } catch (\Exception $e) {
      $message = strpos($e->getMessage(), 'Duplicate') !== false
        ? $request->name . ' already exists'
        : $e->getMessage();
      return response()->json([
        'status' => 'error',
        'message' => $message,
      ]);
    }
  }
}

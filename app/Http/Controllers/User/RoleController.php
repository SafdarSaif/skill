<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    if (Auth::check() && Auth::user()->hasPermissionTo('view roles')) {
      // $roles = Role::whereNotIn('name', ['Super Admin'])->orderBy('id', 'asc')->get();
      $roles = Role::orderBy('id', 'asc')->get();
      return view('user.role.index', compact('roles'));
    } else {
      return response()->view('errors.403', [], 403);
    }
  }

  public function create()
  {
    $user = Auth::user();
    if (Auth::check() && Auth::user()->hasPermissionTo('create roles')) {
      // $permissions = Permission::whereNotIn('name', ['view permissions', 'create permissions', 'edit permissions', 'delete permissions'])->orderBy('id', 'asc')->get();
      $permissions = Permission::orderBy('id', 'asc')->get();
      $permissionData = array();

      foreach ($permissions as $permission) {
        $permissionName = explode(" ", $permission->name);
        $permissionData[$permissionName[1]][$permission->id] = ucwords($permissionName[0]);
      }

      return view('user.role.create', compact('permissionData'));
    } else {
      return response()->view('errors.403', [], 403);
    }
  }

  public function store(RoleRequest $request)
  {
    $user = Auth::user();
    if (Auth::check() && Auth::user()->hasPermissionTo('create roles')) {
      try {
        $role = Role::create(['name' => $request->name]);

        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return response()->json([
          'status' => 'success',
          'message' => $request->name . ' created successfully!',
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
    } else {
      return response()->view('errors.403', [], 403);
    }
  }

  public function edit($id)
  {
    $user = Auth::user();
    if (Auth::check() && Auth::user()->hasPermissionTo('edit roles')) {
      // $permissions = Permission::whereNotIn('name', ['view permissions', 'create permissions', 'edit permissions', 'delete permissions'])->orderBy('id', 'asc')->get();
      $permissions = Permission::orderBy('id', 'asc')->get();
      $permissionData = array();

      foreach ($permissions as $permission) {
        $permissionName = explode(" ", $permission->name);
        $permissionData[$permissionName[1]][$permission->id] = ucwords($permissionName[0]);
      }

      $role = Role::find($id);
      $allotedPermissions = $role->permissions->pluck('id')->toArray();
      return view('user.role.edit', compact(['role', 'allotedPermissions', 'permissionData']));
    }else {
      return response()->view('errors.403', [], 403);
    }
  }

  public function update(RoleRequest $request)
  {
    $user = Auth::user();
    if (Auth::check() && Auth::user()->hasPermissionTo('edit roles')) {
      try {
        $role = Role::find($request->id);
        $role->name = $request->name;
        $role->save();

        $alreadyAssinedPermissions = $role->permissions;
        foreach($alreadyAssinedPermissions as $alreadyAssinedPermission){
          $role->revokePermissionTo($alreadyAssinedPermission);
        }

        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return response()->json([
         'status' =>'success',
         'message' => $request->name .' updated successfully!',
        ]);
      } catch (\Exception $e) {
        $message = strpos($e->getMessage(), 'Duplicate')!== false
         ? $request->name .' already exists'
          : $e->getMessage();
        return response()->json([
         'status' => 'error',
         'message' => $message,
        ]);
      }
    }else {
      return response()->view('errors.403', [], 403);
    }
  }
}

<?php

namespace App\Http\Controllers\API\ROLE;

use App\Http\Controllers\API\USER\UserController;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('read-role')) {
            $roles = Role::all();

            return response()->json($roles);
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //create de role
        $role = new Role();
        $role->name = $request->name;
        $role->label = $request->label;
        $role->color = $request->color;
        $role->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('read-role')) {
            //get permissions of role
            $role = Role::find($request->RoleId);
            $permissions = $role->permissions;

            return response()->json($permissions);
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPermission(Request $request)
    {
        //add permission to role
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('update-role')) {
            $role = Role::find($request->RoleId);
            $permission = Permission::find($request->PermissionId);
            if ($role && $permission) {
                $role->permissions()->attach($permission);

                return response()->json(['success' => 'Permission added.']);
            } else {
                return response()->json(['error' => 'Role or Permission not found.'], 404);
            }
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePermission(Request $request)
    {
        //delete permission from role
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('update-role')) {
            $role = Role::find($request->RoleId);
            $permission = Permission::find($request->PermissionId);
            if ($role && $permission) {
                $role->permissions()->detach($permission);

                return response()->json(['success' => 'Permission deleted.']);
            } else {
                return response()->json(['error' => 'Role or Permission not found.'], 404);
            }
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        //delete a role
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('delete-role')) {
            $role = Role::find($request->RoleId);
            if ($role) {
                $role->delete();

                return response()->json(['success' => 'Role deleted.']);
            } else {
                return response()->json(['error' => 'Role not found.'], 404);
            }
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }
}

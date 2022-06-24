<?php

namespace App\Http\Controllers\API\USER;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //get all users
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('read-user')) {
            $users = User::all();
            return response()->json($users);
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        //display user
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('read-user')) {
            $userf = User::find($request->id);
            if ($userf) {
                return response()->json($userf);
            } else {
                return response()->json(['error' => 'User not found.'], 404);
            }
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        //Update profile image
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user) {
            if ($request->hasFile('avatar')) {
                $filename = $request->avatar->getClientOriginalName();
                $request->avatar->storeAs('images', $filename, 'public');
                $image = Image::make(public_path('storage/images/' . $filename));
                $image->fit(300);
                $image->save();
                $user->avatar = "/storage/images/" . $filename;
            }
            $user->first_name != null ? $user->first_name = $request->first_name : $user->first_name = $user->first_name;
            $user->last_name != null ? $user->last_name = $request->last_name : $user->last_name = $user->last_name;
            if ($request->email) {
                $user_exist = User::where('email', $request->email)->first();
                if ($request->email != $user->email && $user_exist == null && $user->hasPerm('update-user')) {
                    $user->email = $request->email;
                    $user->email_verified_at = null;
                    $user->update();
                    $user->sendEmailVerificationNotification();
                    return response()->json(['success' => 'Email has been changed. Please verify your new email.'], 200);
                } else {
                    return response()->json(['error' => 'Email already exist.'], 403);
                }
            } else {
                $user->email = $user->email;
                $user->update();
                return response()->json(['success' => 'Profile has been updated.'], 200);
            }
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, User $user)
    //Delete user
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('delete-user')) {
            $userd = User::find($request->id);
            if ($userd) {
                $userd->delete();
                return response()->json(['success' => 'User deleted successfully.']);
            } else {
                return response()->json(['error' => 'User not found.'], 404);
            }
            return response()->json(['success' => 'User deleted.']);
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }
    public static function verify_token($api_token): User | bool
    {
        $user = User::where('api_token', $api_token)->first();
        if (!$user) {
            return false;
        }
        return $user;
    }
    public function addRole(Request $request)
    {
        //
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user && $user->hasPerm('update-user')) {

            $user = User::find($request->UserId);
            $role = Role::find($request->RoleId);
            if ($user) {
                $user->roles()->attach($role);
                return response()->json(['success' => 'Role has been added.']);
            } else {
                return response()->json(['error' => 'User not found.'], 404);
            }
        } else {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        }
    }
}

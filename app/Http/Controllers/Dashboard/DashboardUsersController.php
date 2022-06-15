<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class DashboardUsersController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('dashboard.users', compact('users'));
    }

    public function show(User $user)
    {
        $users = User::all();
        $roles = Role::all();

        return view('dashboard.users', compact('users', 'roles', 'user'));
    }
}

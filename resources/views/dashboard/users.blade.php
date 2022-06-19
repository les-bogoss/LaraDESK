@extends('layouts.app')
@section('title', 'Users ' . (isset($user) ? '(' . strtoupper($user->last_name) . ' ' . $user->first_name . ')' : '') .
    ' Dashboard - ' . config('app.name', 'Laravel'))

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-users">
            <table class="dashboard-users-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <div class="users-wrapper">
                        @foreach ($users as $u)
                            <tr onclick="window.location.href = '{{ route('users.show', ['user' => $u]) }}'"
                                class="{{ isset($user) && $u == $user ? 'active' : 'inactive' }}">
                                <td><img src="{{ $u->avatar }}" alt="{{ $u->first_name }} {{ $u->last_name }} avatar"
                                        width="50px"></td>
                                <td>{{ strtoupper($u->last_name) }} {{ $u->first_name }}</td>
                                <td>{{ $u->email }}</td>
                            </tr>
                        @endforeach
                    </div>
                </tbody>
            </table>
        </div>
        <div class="dashboard-user">
            @isset($user)
                <div class="dashboard-user-info">
                    <div class="dashboard-user-info-name">
                        <h3>{{ strtoupper($user->last_name) }} {{ $user->first_name }}</h3>
                        <p>{{ $user->email }}</p>
                        <div class="dashboard-user-info-settings">
                            @if (Auth::user()->hasPerm('update-user'))
                                <a href="">EDIT</a>
                            @endif
                            @if (Auth::user()->hasPerm('delete-user'))
                                <form action="{{ route('users.destroy', ['user' => $user]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">DELETE</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <img src="{{ $user->avatar }}" alt="{{ $user->first_name }} {{ $user->last_name }} avatar"
                        width="100px">
                </div>
                <div class="dashboard-user-info">
                    <form action="{{ route('users.update', ['user' => $user]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="dashboard-user-info-name">
                            <input type="text" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name">
                            <input type="text" name="first_name" value="{{ $user->first_name }}" placeholder="First Name">
                            <input type="text" name="email" value="{{ $user->email }}" placeholder="Email">
                            <div class="dashboard-user-info-settings">
                                <button type="submit">SAVE</button>
                                <button>CANCEL</button>
                            </div>
                        </div>
                        <img src="{{ $user->avatar }}" alt="{{ $user->first_name }} {{ $user->last_name }} avatar"
                            width="100px">
                    </form>
                </div>
                <div class="dashboard-user-roles">
                    <h3>Roles :</h3>
                    @if (Auth::user()->hasPerm('update-user'))
                        <form action="{{ route('users.addRole', ['user' => $user]) }}" method="post">
                            @csrf
                            <select name="role_id" id="role_id">
                                <option value="">Select a role</option>
                                @foreach ($roles as $role)
                                    @if (!isset(
                                        $user->roles()->get()->where('id', $role->id)->first()->id,
                                    ))
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button class="add-role-button" type="submit">ADD</button>
                        </form>
                    @endif
                    <div class="dashboard-user-roles-have">
                        @foreach ($user->roles()->get() as $role)
                            @if (Auth::user()->hasPerm('update-user'))
                                <form action="{{ route('users.removeRole', ['user' => $user]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                                    <button class="delete-role-button" type="submit">{{ $role->name }} X</button>
                                </form>
                            @else
                                <button class="delete-role-button">{{ $role->name }}</button>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="dashboard-user-tickets">
                    <h3>Tickets :</h3>
                    <table>
                        <tbody>
                            @foreach ($user->ticket()->get()->sortByDesc('updated_at')
            as $ticket)
                                <tr>
                                    <td><span>[{{ $ticket->id }}]</span> - {{ $ticket->title }}</td>
                                    <td>
                                        @if (Auth::user()->hasPerm('read-ticket'))
                                            <button>SHOW</button>
                                        @endif
                                        @if (Auth::user()->hasPerm('delete-ticket'))
                                            <form action="{{ route('tickets.destroy', ['ticket' => $ticket]) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">DELETE</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            @endisset
        </div>
    </div>
@endsection

@extends('layouts.app')

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
                    @foreach ($users as $u)
                        <tr onclick="window.location.href = '{{ route('dashboardUsers.show', ['user' => $u]) }}'"
                            class="{{ isset($user) && $u == $user ? 'active' : 'inactive' }}">
                            <td><img src="{{ $u->avatar }}" alt="{{ $u->first_name }} {{ $u->last_name }} avatar"
                                    width="50px"></td>
                            <td>{{ $u->first_name }} {{ $u->last_name }}</td>
                            <td>{{ $u->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="dashboard-user">
            @isset($user)
                <div class="dashboard-user-info">
                    <div class="dashboard-user-info-name">
                        <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>
                        <p>{{ $user->email }}</p>
                    </div>
                    <img src="{{ $user->avatar }}" alt="{{ $user->first_name }} {{ $user->last_name }} avatar"
                        width="100px">
                </div>
                <div class="dashboard-user-roles">
                    <h3>Roles :</h3>
                    <form action="{{ route('User.addRole', ['user' => $user]) }}" method="post">
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
                    <div class="dashboard-user-roles-have">
                        @foreach ($user->roles()->get() as $role)
                            <form action="{{ route('User.removeRole', ['user' => $user]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="role_id" value="{{ $role->id }}">
                                <button class="delete-role-button" type="submit">{{ $role->name }} X</button>
                            </form>
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
                                    <td><button>SHOW</button><button>DELETE</button></td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            @endisset
        </div>
    </div>
@endsection

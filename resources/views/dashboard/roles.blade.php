@extends('layouts.app')

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-roles">
            <table class="dashboard-roles-table">
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $r)
                        <tr onclick="window.location.href = '{{ route('roles.show', ['role' => $r]) }}'"
                            class="{{ isset($role) && $r == $role ? 'active' : 'inactive' }}">
                            <td>{{ strtoupper($r->name) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="dashboard-role">
            @isset($role)
                <div class="dashboard-role-info">
                    <div class="dashboard-role-info-name">
                        <h3>{{ $role->name }}</h3>
                        <div class="dashboard-role-info-settings">
                            <a href="{{ route('roles.edit', ['role' => $role]) }}">EDIT</a>
                            <form action="{{ route('roles.destroy', ['role' => $role]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit">DELETE</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="dashboard-role-roles">
                    <h3>Permissions :</h3>
                    <form action="{{ route('roles.addPermission', ['role' => $role]) }}" method="post">
                        @csrf
                        <select name="permission_id" id="permission_id">
                            <option value="">Select a permission</option>
                            @foreach ($permissions as $permission)
                                @if (!isset(
                                    $role->permissions()->get()->where('id', $permission->id)->first()->id,
                                ))
                                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button class="add-role-button" type="submit">ADD</button>
                    </form>
                    <div class="dashboard-role-roles-have">
                        @foreach ($role->permissions()->get() as $permission)
                            <form action="{{ route('roles.removePermission', ['role' => $role]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                                <button class="delete-role-button" type="submit">{{ $permission->name }} X</button>
                            </form>
                        @endforeach
                    </div>
                </div>
                <div class="dashboard-role-tickets">
                    <h3>User have the role :</h3>
                    <table>
                        <tbody>
                            @foreach ($role->users()->get() as $user)
                                <tr>
                                    <td>{{ strtoupper($user->last_name) }} {{ $user->first_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <form action="{{ route('users.destroy', ['user' => $user]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">DELETE</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            @endisset
        </div>
    </div>
@endsection

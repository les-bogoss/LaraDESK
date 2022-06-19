@extends('layouts.app')
@section('title', 'Roles ' . (isset($role) ? '(' . $role->name . ')' : '') . ' Dashboard - ' . config('app.name',
    'Laravel'))

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-roles">
            @if (Auth::user()->hasPerm('create-role'))
                <div class="dashboard-roles-create">
                    <button id="createRoleButton">Create Role</button>
                </div>
            @endif
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
                            @if (Auth::user()->hasPerm('update-role'))
                                <a href="{{ route('roles.edit', ['role' => $role]) }}">EDIT</a>
                            @endif
                            @if (Auth::user()->hasPerm('delete-role'))
                                <form action="{{ route('roles.destroy', ['role' => $role]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">DELETE</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="dashboard-role-roles">
                    <h3>Permissions :</h3>
                    @if (Auth::user()->hasPerm('update-role'))
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
                    @endif
                    <div class="dashboard-role-roles-have">
                        @foreach ($role->permissions()->get() as $permission)
                            @if (Auth::user()->hasPerm('update-role'))
                                <form action="{{ route('roles.removePermission', ['role' => $role]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                                    <button class="delete-role-button" type="submit">{{ $permission->name }} X</button>
                                </form>
                            @else
                                <button class="delete-role-button">{{ $permission->name }}</button>
                            @endif
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
                                        @if (Auth::user()->hasPerm('update-user'))
                                            <form action="{{ route('users.removeRole', ['user' => $user]) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                <button type="submit">REMOVE</button>
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
    @if (Auth::user()->hasPerm('create-role'))
        <div id="createRole" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2>Create Role :</h2>
                </div>
                <form action="{{ route('roles.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="name">
                            <label for="name">Name :</label>
                            <input type="text" class="@error('name') error @enderror" name="name" id="name" placeholder="Name" value="{{ old("name") }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="label">
                            <label for="label">Label :</label>
                            <input type="text" class="@error('label') error @enderror" name="label" id="label" placeholder="Label" value="{{ old("label") }}">
                            @error('label')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="color">
                            <label for="color">Color :</label>
                            <input type="color" class="@error('color') error @enderror" name="color" id="color" placeholder="Color" value="{{ old("color") }}">
                            @error('color')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit">CREATE</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            var modal = document.getElementById("createRole");

            // Get the button that opens the modal
            var btn = document.getElementById("createRoleButton");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on the button, open the modal
            btn.onclick = function() {
                modal.style.display = "flex";
            }
            @error('*')
                modal.style.display = "flex";
            @enderror
            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    @endif
@endsection

@extends('layouts.app')
@section('title', 'Roles ' . (isset($role) ? '(' . $role->name . ')' : '') . ' Dashboard - ' . config('app.name',
    'Laravel'))

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-roles">
            @if (Auth::user()->hasPerm('create-role'))
                <div class="dashboard-roles-create">
                    <button name="createRoleButton">Create Role</button>
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
                <div class="dashboard-role-info" id="info">
                    <div class="dashboard-role-info-name">
                        <h3>{{ $role->name }}</h3>
                        <p>{{ $role->label }}</p>
                        <div class="color-container">
                            <p>Color :</p>
                            <div style="background-color: {{ $role->color }};" class="role-color"></div>
                        </div>
                        <div class="dashboard-role-info-settings">
                            @if (Auth::user()->hasPerm('update-role'))
                                <button onclick="edit()">EDIT</button>
                            @endif
                            @if (Auth::user()->hasPerm('delete-role'))
                                <button name="warningButton" data-msg="to delete the role <strong>{{ $role->name }}</strong>" data-method="DELETE"
                                    data-route="{{ route('roles.destroy', ['role' => $role]) }}">DELETE</button>
                            @endif
                        </div>
                    </div>
                </div>
                <form action="{{ route('roles.update', ['role' => $role]) }}" method="post" style="display: none;"
                    id="edit" class="edit-form">
                    @csrf
                    @method('PUT')
                    <div class="dashboard-role-info editable">
                        <div class="dashboard-role-info-name">
                            <div class="edit-form-container">
                                <label for="name">Name :</label>
                                <input type="text" name="name" id="name" value="{{ $role->name }}">
                            </div>
                            <div class="edit-form-container">
                                <label for="label">Label :</label>
                                <input type="text" name="label" id="label" value="{{ $role->label }}">
                            </div>
                            <div class="edit-form-container">
                                <label for="color">Color :</label>
                                <input type="color" name="color" id="color" value="{{ $role->color }}">
                            </div>
                            <div class="dashboard-role-info-settings">
                                <button type="submit">SAVE</button>
                                <button type="button" onclick="cancel()">CANCEL</button>
                            </div>
                        </div>
                    </div>
                </form>
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
                                <button name="warningButton" class="delete-role-button"
                                data-msg="to delete the permission <strong>{{ $permission->name }}</strong>"
                                data-method="DELETE"
                                data-route="{{ route('roles.removePermission', ['role' => $role, 'permission_id' => $permission->id]) }}">{{ $permission->name }} X</button>
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
                                        @if (Auth::user()->hasPerm('read-user'))
                                            <a href="{{ route('users.show', ['user' => $user]) }}">SHOW</a>
                                        @endif
                                        @if (Auth::user()->hasPerm('update-user'))
                                            <button name="warningButton"
                                                data-msg="remove the role <strong>{{ $role->name }}</strong> of <strong>{{ strtoupper($user->last_name) }} {{ $user->first_name }}</strong>"
                                                data-method="DELETE"
                                                data-route="{{ route('users.removeRole', ['user' => $user]) }}">REMOVE</button>
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
    <div id="warning" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Warning :</h2>
            </div>
            <form action="" method="post" id="warning_form">
                @csrf
                @method('')
                <div class="modal-body">
                    <p>Are you sure you want <span id="warning_message"></span> ?</p>
                </div>
                <div class="modal-footer warning-footer">
                    <button type="submit">Yes</button>
                    <button type="button" class="close">No</button>
                </div>
            </form>
        </div>
    </div>
    
        <div id="createRole" class="modal">
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
                            <input type="text" class="@error('name') error @enderror" name="name" id="name"
                                placeholder="Name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="label">
                            <label for="label">Label :</label>
                            <input type="text" class="@error('label') error @enderror" name="label" id="label"
                                placeholder="Label" value="{{ old('label') }}">
                            @error('label')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="color">
                            <label for="color">Color :</label>
                            <input type="color" class="@error('color') error @enderror" name="color" id="color"
                                placeholder="Color" value="{{ old('color') }}">
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
            var modal = document.querySelectorAll('.modal')
            modal.forEach(element => {
                var span = element.querySelectorAll('.close');
                var open_button = document.getElementsByName(element.id + "Button")

                open_button.forEach(button => {
                    button.addEventListener('click', () => {
                        element.style.display = "flex";
                        if (element.id == "warning") {
                        element.querySelectorAll("#warning_message").forEach(element => {
                            element.innerHTML = button.dataset.msg;
                            document.getElementById("warning_form").action = button.dataset.route;
                            document.getElementById("warning_form").querySelector("[name=_method]")
                                .value = button.dataset.method;
                        });
                    }
                    });

                    span.forEach(bt => {
                        bt.addEventListener('click', () => {
                            element.style.display = "none";
                        });
                    });
                    

                    if (element.id == "createRole") {
                        @error('*')
                            modal.style.display = "flex";
                        @enderror
                    }
                })
            })
            window.onclick = function(event) {
                modal.forEach(element => {
                    if (event.target == element) {
                        element.style.display = "none";
                    }
                });
            }

            function edit() {
                document.getElementById('info').style.display = 'none';
                document.getElementById('edit').style.display = '';
            }

            function cancel() {
                document.getElementById('info').style.display = '';
                document.getElementById('edit').style.display = 'none';
            }
        </script>
    @endif
@endsection

@extends('layouts.app')
@section('title', 'Roles ' . (isset($role) ? '(' . $role->name . ')' : '') . ' Dashboard - ' . config('app.name',
    'Laravel'))

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-roles">
            @if (Auth::user()->hasPerm('create-role'))
                <div class="dashboard-roles-create">
                    <x-button name="createRoleButton" color="primary">Create Role</x-button>
                </div>
            @endif
            <div class="roles-wrapper" id="roles-list">
                @foreach ($roles as $r)
                    <div onclick="window.location.href = '{{ route('roles.show', ['role' => $r]) }}'"
                        class="role-item {{ isset($role) && $r == $role ? 'active' : 'inactive' }}"
                        id="role-{{ $r->id }}">
                        <div class="role-item-name">{{ $r->name }}</div>
                    </div>
                @endforeach
            </div>
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
                                <x-button color="primary" onclick="edit()">EDIT</x-button>
                            @endif
                            @if (Auth::user()->hasPerm('delete-role'))
                                <x-button color="danger" name="warningButton"
                                    data-msg="to delete the role <strong>{{ $role->name }}</strong>" data-method="DELETE"
                                    data-route="{{ route('roles.destroy', ['role' => $role]) }}">DELETE</x-button>
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
                                <x-button color="primary" type="submit">SAVE</x-button>
                                <x-button color="secondary" type="button" onclick="cancel()">CANCEL</x-button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="dashboard-role-roles">
                    <h3>Permissions :</h3>
                    @if (Auth::user()->hasPerm('update-role'))
                        <form class="add-perm-form" action="{{ route('roles.addPermission', ['role' => $role]) }}"
                            method="post">
                            @csrf
                            <x-select color="secondary" name="permission_id" id="permission_id">
                                <option value="">Select a permission</option>
                                @foreach ($permissions as $permission)
                                    @if (!isset(
                                        $role->permissions()->get()->where('id', $permission->id)->first()->id))
                                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                    @endif
                                @endforeach
                            </x-select>
                            <x-button color="primary" type="submit">ADD</x-button>
                        </form>
                    @endif
                    <div class="dashboard-role-roles-have">
                        @foreach ($role->permissions()->get() as $permission)
                            @if (Auth::user()->hasPerm('update-role'))
                                <button name="warningButton" class="delete-role-button"
                                    data-msg="to delete the permission <strong>{{ $permission->name }}</strong>"
                                    data-method="DELETE"
                                    data-route="{{ route('roles.removePermission', ['role' => $role, 'permission_id' => $permission->id]) }}">{{ $permission->name }}
                                    X</button>
                            @else
                                <button class="delete-role-button">{{ $permission->name }}</button>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="dashboard-role-tickets">
                    <h3>User owning the role :</h3>
                    <table>
                        <tbody>
                            @foreach ($role->users()->get() as $user)
                                <tr>
                                    <td>{{ strtoupper($user->last_name) }} {{ $user->first_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if (Auth::user()->hasPerm('read-user'))
                                            <a href="{{ route('users.show', ['user' => $user]) }}">
                                                <x-button color="primary">VIEW</x-button>
                                            </a>
                                        @endif
                                        @if (Auth::user()->hasPerm('update-user'))
                                            <x-button color="danger" name="warningButton"
                                                data-msg="remove the role <strong>{{ $role->name }}</strong> of <strong>{{ strtoupper($user->last_name) }} {{ $user->first_name }}</strong>"
                                                data-method="DELETE"
                                                data-route="{{ route('users.removeRole', ['user' => $user, 'role_id' => $role]) }}">REMOVE
                                            </x-button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
                <script>
                    let roles = document.querySelector('.dashboard-roles');
                    console.log(roles);

                    function resizeMobileRoles() {
                        if (window.matchMedia("(max-width: 1024px)").matches) {
                            roles.style.display = 'none';
                        } else {
                            roles.style.display = 'block';
                        }
                    }

                    window.onresize = resizeMobileRoles;
                    resizeMobileRoles();
                </script>

                <a href="{{ route('roles.index') }}">
                    <button class="return">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </button>
                </a>
            @else
                <script>
                    let role = document.querySelector('.dashboard-role');
                    console.log(role);

                    function resizeMobileRole() {
                        if (window.matchMedia("(max-width: 1024px)").matches) {
                            role.style.display = 'none';
                        } else {
                            role.style.display = 'block';
                        }
                    }

                    window.onresize = resizeMobileRole;
                    resizeMobileRole();
                </script>
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
                        <x-button color="secondary-outline" type="submit">Yes</x-button>
                        <x-button color="danger" type="button" class="close">No</x-button>
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
                        <x-button color="primary" type="submit">CREATE</x-button>
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
                                document.getElementById("warning_form").action = button.dataset
                                    .route;
                                document.getElementById("warning_form").querySelector(
                                        "[name=_method]")
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

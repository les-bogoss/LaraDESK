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
                                class="{{ isset($user) && $u == $user ? 'active' : 'inactive' }}"
                                id="user-{{ $u->id }}">
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
                <div class="dashboard-user-info" id="info">
                    <div class="dashboard-user-info-name">
                        <h3>{{ strtoupper($user->last_name) }} {{ $user->first_name }}</h3>
                        <p>{{ $user->email }}
                            @if ($user->email_verified_at != null)
                                <i class="fa-solid fa-square-check"></i>
                            @endif
                        </p>
                        <div class="dashboard-user-info-settings">
                            @if (Auth::user()->hasPerm('update-user'))
                                <button onclick="edit()" class="dashboard-user-info-settings-edit">EDIT</button>
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
                <form action="{{ route('users.update', ['user' => $user]) }}" method="post" style="display: none;"
                    id="edit" class="edit-form">
                    @csrf
                    @method('PUT')
                    <div class="dashboard-user-info editable">
                        <div class="dashboard-user-info-name">
                            <div class="name">
                                <input type="text" name="last_name" value="{{ $user->last_name }}"
                                    placeholder="Last Name">
                                <input type="text" name="first_name" value="{{ $user->first_name }}"
                                    placeholder="First Name">
                            </div>
                            <input type="text" name="email" value="{{ $user->email }}" placeholder="Email">
                            <div class="dashboard-user-info-settings">
                                <button type="submit">SAVE</button>
                                <button type="button" onclick="cancel()">CANCEL</button>
                            </div>
                        </div>
                        <img src="{{ $user->avatar }}" alt="{{ $user->first_name }} {{ $user->last_name }} avatar"
                            width="100px">
                    </div>
                </form>
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
                                            <a href="{{ route('tickets.show', ['ticket' => $ticket]) }}">VIEW</a>
                                        @endif
                                        @if (Auth::user()->hasPerm('delete-ticket'))
                                            <button name="warningButton"
                                                data-msg="Are you sure you want to delete this ticket" data-method="DELETE"
                                                data-route="{{ route('tickets.destroy', ['ticket' => $ticket]) }}">DELETE</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            @endisset
        </div>
    </div>
    @isset($user)
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

            var user = document.getElementById('user-{{ $user->id }}');
            document.getElementsByClassName('dashboard-users')[0].scrollTo(0, user.offsetTop - 25);

            var textarea = document.getElementById("input-content");

            function edit() {
                document.getElementById('info').style.display = 'none';
                document.getElementById('edit').style.display = '';
            }

            function cancel() {
                document.getElementById('info').style.display = '';
                document.getElementById('edit').style.display = 'none';
            }
        </script>
    @endisset
@endsection

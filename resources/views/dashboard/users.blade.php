@extends('layouts.app')
@section('title', 'Users ' . (isset($user) ? '(' . strtoupper($user->last_name) . ' ' . $user->first_name . ')' : '') .
    ' Dashboard - ' . config('app.name', 'Laravel'))

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-users">
            <div class="dashboard-users-wrapper">
                <div id="search">
                    <input id="search-bar" onkeyup="searchUsers()" /><i class="fa fa-search"></i>
                </div>
                <script>
                    function searchUsers() {
                        // Declare variables
                        var input, filter, ul, li, a, i, txtValue;
                        filter = document.getElementById('search-bar').value.toUpperCase();
                        ul = document.getElementById("users-list");
                        li = ul.getElementsByClassName('user-item');

                        // Loop through all list items, and hide those who don't match the search query
                        for (i = 0; i < li.length; i++) {
                            a = li[i].getElementsByTagName("div")[0].getElementsByTagName("div")[0].getElementsByTagName("h3")[0];
                            txtValue = a.textContent || a.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                li[i].style.display = "";
                            } else {
                                li[i].style.display = "none";
                            }
                        }
                    }
                </script>
                <div class="users-wrapper" id="users-list">
                    @foreach ($users as $u)
                        <div onclick="window.location.href = '{{ route('users.show', ['user' => $u]) }}'"
                            class="user-item {{ isset($user) && $u == $user ? 'active' : 'inactive' }}"
                            id="user-{{ $u->id }}">
                            <div class="user-details"><img src="{{ $u->avatar }}"
                                    alt="{{ $u->first_name }} {{ $u->last_name }} avatar" width="50px">
                                <div>
                                    <h3 class="name">{{ strtoupper($u->last_name) }} {{ $u->first_name }}</h3>
                                    <p class="email">{{ $u->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
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
                                <x-button color="primary" onclick="edit()" class="dashboard-user-info-settings-edit">EDIT</x-button>
                            @endif
                            @if (Auth::user()->hasPerm('delete-user'))
                                <x-button color="danger" name="warningButton" class="delete-role-button"
                                    data-msg="to delete the user <strong>{{ strtoupper($user->last_name) }} {{ $user->first_name }}</strong>"
                                    data-method="DELETE"
                                    data-route="{{ route('users.destroy', ['user' => $user]) }}">DELETE</x-button>
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
                                <x-button color="primary" type="submit">SAVE</x-button>
                                <x-button color="secondary" type="button" onclick="cancel()">CANCEL</x-button>
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
                            <x-select color="secondary" name="role_id" id="role_id">
                                <option value="">Select a role</option>
                                @foreach ($roles as $role)
                                    @if (!isset(
                                        $user->roles()->get()->where('id', $role->id)->first()->id))
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </x-select>
                            <x-button color="primary" class="add-role-button" type="submit">ADD</x-button>
                        </form>
                    @endif
                    <div class="dashboard-user-roles-have">
                        @foreach ($user->roles()->get() as $role)
                            @if (Auth::user()->hasPerm('update-user'))
                                <button name="warningButton" class="delete-role-button"
                                    data-msg="to remove the <strong>{{ $role->name }}</strong> role of <strong>{{ strtoupper($user->last_name) }} {{ $user->first_name }}</strong>"
                                    data-method="DELETE"
                                    data-route="{{ route('users.removeRole', ['user' => $user, 'role_id' => $role]) }}">{{ $role->name }}
                                    X</button>
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
                                            <x-button color="primary"><a href="{{ route('tickets.show', ['ticket' => $ticket]) }}">VIEW</a></x-button>
                                        @endif
                                        @if (Auth::user()->hasPerm('delete-ticket'))
                                            <x-button color="danger" name="warningButton" data-msg="to delete this ticket" data-method="DELETE"
                                                data-route="{{ route('tickets.destroy', ['ticket' => $ticket]) }}">DELETE</x-button>
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
                        <x-button color="primary-outline" type="submit">Yes</x-button>
                        <x-button color="danger" type="button" class="close">No</x-button>
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
            document.getElementsByClassName('dashboard-users')[0].scrollTo(0, user.offsetTop - 155);

            var textarea = document.getElementById("input-content");

            function edit() {
                document.getElementById('info').style.display = 'none';
                document.getElementById('edit').style.display = '';
            }

            function cancel() {
                document.getElementById('info').style.display = '';
                document.getElementById('edit').style.display = 'none';
            }

            console.log("user set")

            const users = document.querySelector('.dashboard-users');

            function resizeMobileUsers() {
                if (window.matchMedia("(max-width: 1024px)").matches) {
                    users.style.display = 'none';
                } else {
                    users.style.display = 'block';
                }
            }

            window.onresize = resizeMobileUsers;
            resizeMobileUsers();
        </script>

        <a href="{{ route('users.index') }}">
            <button class="return">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </button>
        </a>
    @else
        <script>
            console.log("user not set")

            const user = document.querySelector('.dashboard-user');

            function resizeMobileUser() {
                if (window.matchMedia("(max-width: 1024px)").matches) {
                    user.style.display = 'none';
                } else {
                    user.style.display = 'block';
                }
            }

            window.onresize = resizeMobileUser;
            resizeMobileUser();
        </script>
    @endisset
@endsection

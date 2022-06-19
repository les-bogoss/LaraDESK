@extends('layouts.app')
@section('title', 'Account - ' . config('app.name', 'Laravel'))

@section('content')

    <div class="account-container">
        <div>
            <h1>Account</h1>
            {{-- <p>
                <a href="{{ route("account.edit") }}">Edit</a>
            </p> --}}

            <h2>Profile</h2>
            <div class="account-profile">
                <button onclick="edit()" class="account-edit">EDIT</button>
                <script>
                    let hidden = "hidden";
                    function edit() {
                        if (hidden == "hidden") {
                            document.getElementById("account-modal").style.visibility = "visible";
                            document.getElementById("account-modal").style.opacity = "1";
                            hidden = "visible";
                        } else {
                            document.getElementById("account-modal").style.visibility = "hidden";
                            document.getElementById("account-modal").style.opacity = "0";

                            hidden = "hidden";
                        }
                    }
                </script>
                <div class="account-profile-avatar">
                    <img src="{{ Auth::user()->avatar }}" alt="">
                </div>
                <p>
                    <strong>Name:</strong> {{ $user->first_name . ' ' . $user->last_name }}
                </p>
                <p>
                    <strong>Email:</strong> {{ $user->email }}
                </p>
                <p>
                    <strong>Created at:</strong> {{ $user->created_at }}
                </p>
                <p>
                    <strong>Updated at:</strong> {{ $user->updated_at }}
                </p>

                <h2>Roles</h2>
                <p>
                    @foreach ($user->roles as $role)
                        <span class="badge badge-primary">{{ $role->name }}</span>
                    @endforeach
                </p>


                <h2>Permissions</h2>
                <p>
                    @foreach ($user->roles as $role)
                        @foreach ($role->permissions as $permissions)
                            <span class="badge badge-primary">{{ $permissions->name }}</span>
                        @endforeach
                    @endforeach
                </p>

                <h2>Tokens</h2>
                <p>
                    <span class="badge badge-primary">{{ $user->api_token }}</span>

                </p>

            </div>
        </div>
    </div>
    <div class="account-modal" id="account-modal">
        <div class="account-modal-body">
            <div class="account-modal-header">
                <div class="account-modal-header-title">
                    <h1>Account</h1>
                </div>
                <div class="account-modal-header-close">
                    <button onclick="edit()" class="account-modal-header-close-button">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="account-modal-body-content">
                <div class="account-modal-body-content-avatar">
                    <img src="{{ Auth::user()->avatar }}" id="output" alt="">
                </div>
                <div class="account-modal-body-content-form" enctype="multipart/form-data">
                    <form action="{{ route('account.updateProfilePicture') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="account-modal-body-content-form-input">
                            <label for="avatar">Avatar</label>
                            <input type="file" name="image" accept="image/*" onchange="loadFile(event)">
                            <script>
                                var loadFile = function(event) {
                                    var output = document.getElementById('output');
                                    output.src = URL.createObjectURL(event.target.files[0]);
                                    output.onload = function() {
                                        URL.revokeObjectURL(output.src)
                                    }
                                };
                            </script>
                        </div>
                        <div class="account-modal-body-content-form-submit">
                            <button type="submit" class="account-modal-body-content-form-submit-button">
                                <i class="fa-solid fa-save"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

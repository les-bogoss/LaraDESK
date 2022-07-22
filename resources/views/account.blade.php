@extends('layouts.app')
@section('title', 'Account - ' . config('app.name', 'Laravel'))

@section('content')

<div class="account-bg">
<div class="account-container">
    <div>
        <x-button color="primary">primary</x-button>
        <x-button color="primary-outline">primary-outline</x-button>
        <x-button color="secondary">secondary</x-button>
        <x-button color="secondary-outline">secondary-outline</x-button>
        <x-button color="danger">danger</x-button>
        <x-button color="danger-outline">danger-outline</x-button>
        <x-button color="success">success</x-button>
        <x-button color="success-outline">success-outline</x-button>
        <x-input placeholder="input"/>
        <x-input placeholder="input-error" error="true"/>
        <x-select color="primary">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </x-select>
        <x-select color="secondary">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </x-select>
        <x-select color="danger">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </x-select>
        <x-select color="success">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </x-select>
        <x-select color="warning">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </x-select>
        <h1>Account Settings</h1>
        {{-- <p>
                <a href="{{ route("account.edit") }}">Edit</a>
        </p> --}}

        <h2>Profile</h2>
        <div class="account-profile">
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
            <div class="profile">
                <div class="avatar">
                    <div class="account-profile-avatar">
                        <img src="{{ Auth::user()->avatar }}" alt="">
                    </div>
                    <x-button onclick="edit()" color="primary" class="edit-picture">EDIT</x-button>
                </div>
                <div class="details">
                    <p>
                        <span>Name:</span> {{ $user->first_name . ' ' . $user->last_name }}
                    </p>
                    <p>
                        <span>Email:</span> {{ $user->email }}
                    </p>
                    <p>
                        <span>Created the:</span> {{ $user->created_at->format('d/m/Y') }}
                    </p>
                    <p>
                        <span>Updated the:</span> {{ $user->updated_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
            <div class="roles">
                <h2>Roles</h2>
                @foreach ($user->roles as $role)
                <p class="role" style="background-color: {{ $role->color }}">{{ $role->name }}</p>
                @endforeach
            </div>

            <div class="permissions">
                <h2>Permissions</h2>
                @foreach ($user->roles as $role)
                @foreach ($role->permissions as $permissions)
                <p class="permission">{{ $permissions->name }}</p>
                @endforeach
                @endforeach
            </div>
            <div class="tokens">
                <h2>Tokens</h2>
                <p class="badge badge-primary">{{ $user->api_token }}</p>
            </div>

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
                <form action="{{ route('account.updateProfilePicture') }}" method="POST" enctype="multipart/form-data">
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
</div>
@endsection

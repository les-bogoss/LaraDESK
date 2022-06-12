@props(['errors'])

@if ($errors->any())
    <div class="error-notification">
        <h1 class="title">
            {{ __('Whoops!') }}
        </h1>

        <ul>
            @foreach ($errors->all() as $error)
                <li class="message">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@props(['disabled' => false, 'error' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input'. ((isset($error) && $error) ? ' input-error' : '')]) !!} class="@if(isset($error) && $error)input-error @endif">

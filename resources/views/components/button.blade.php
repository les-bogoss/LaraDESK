<button {!! $attributes->merge(['class' => 'button-component btn-' . $color]) !!}>
    {{ $slot }}
</button>

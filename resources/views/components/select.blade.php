<select {!! $attributes->merge(['class' => 'select select-'. $color]) !!}>
    {{ $slot }}
</select>
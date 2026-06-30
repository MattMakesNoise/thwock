@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-thwock-dark dark:text-thwock-light-muted']) }}>
    {{ $value ?? $slot }}
</label>

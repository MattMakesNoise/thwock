@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-thwock-primary dark:text-thwock-secondary']) }}>
        {{ $status }}
    </div>
@endif

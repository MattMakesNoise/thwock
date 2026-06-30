@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-thwock-primary dark:border-thwock-secondary text-sm font-medium leading-5 text-thwock-darker dark:text-thwock-light focus:outline-none focus:border-thwock-primary transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-thwock-dark dark:text-thwock-light-muted hover:text-thwock-dark dark:hover:text-thwock-light hover:border-thwock-dark/25 dark:hover:border-thwock-secondary focus:outline-none focus:text-thwock-dark dark:focus:text-thwock-light focus:border-thwock-dark/25 dark:focus:border-thwock-secondary transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

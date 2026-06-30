@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-thwock-primary dark:border-thwock-secondary text-start text-base font-medium text-thwock-primary dark:text-thwock-secondary bg-thwock-light-muted dark:bg-thwock-darker focus:outline-none focus:text-thwock-primary dark:focus:text-thwock-secondary focus:bg-thwock-light-muted dark:focus:bg-thwock-darker focus:border-thwock-primary dark:focus:border-thwock-secondary transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-thwock-dark dark:text-thwock-light-muted hover:text-thwock-darker dark:hover:text-thwock-light hover:bg-thwock-light-muted dark:hover:bg-thwock-darker hover:border-thwock-dark/25 dark:hover:border-thwock-secondary focus:outline-none focus:text-thwock-darker dark:focus:text-thwock-light focus:bg-thwock-light-muted dark:focus:bg-thwock-darker focus:border-thwock-dark/25 dark:focus:border-thwock-secondary transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

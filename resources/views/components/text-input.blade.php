@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-thwock-dark/25 dark:border-thwock-light-muted/20 dark:bg-thwock-darker dark:text-thwock-light-muted focus:border-thwock-primary dark:focus:border-thwock-secondary focus:ring-thwock-primary dark:focus:ring-thwock-secondary rounded-md shadow-sm']) }}>

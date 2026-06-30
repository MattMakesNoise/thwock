<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-thwock-light dark:bg-thwock-dark border border-thwock-dark/25 dark:border-thwock-light-muted/30 rounded-md font-semibold text-xs text-thwock-dark dark:text-thwock-light-muted uppercase tracking-widest shadow-sm hover:bg-thwock-light-muted dark:hover:bg-thwock-darker focus:outline-none focus:ring-2 focus:ring-thwock-primary focus:ring-offset-2 dark:focus:ring-offset-thwock-dark disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

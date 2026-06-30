<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-thwock-primary border border-transparent rounded-md font-semibold text-xs text-thwock-light uppercase tracking-widest hover:bg-thwock-secondary hover:text-thwock-darker focus:bg-thwock-secondary focus:text-thwock-darker active:bg-thwock-darker active:text-thwock-light focus:outline-none focus:ring-2 focus:ring-thwock-primary focus:ring-offset-2 dark:focus:ring-offset-thwock-dark transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

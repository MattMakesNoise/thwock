<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-green text-xs uppercase tracking-widest']) }}>
    {{ $slot }}
</button>

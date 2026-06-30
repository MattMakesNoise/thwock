<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-thwock-darker dark:text-thwock-light leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="content py-12">
        <div>
            <div class="bg-thwock-light dark:bg-thwock-dark overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-thwock-darker dark:text-thwock-light">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="content py-12">
        <div>
            <a href="{{ route('rounds.create') }}" class="bg-thwock-primary hover:bg-thwock-secondary hover:text-thwock-darker text-thwock-light font-bold py-6 px-12 rounded">
                {{ __("Start Thwocking!") }}
            </a>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-thwock-darker dark:text-thwock-light leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition.opacity.duration.200ms
        class="fixed right-4 top-20 z-50 rounded bg-thwock-dark px-4 py-3 text-sm font-medium text-thwock-light shadow-lg dark:bg-thwock-light dark:text-thwock-darker"
        role="status"
    >
        <div class="flex items-center gap-3">
            <span>{{ __("You're logged in!") }}</span>
            <button type="button" class="text-current opacity-70 hover:opacity-100" @click="show = false" aria-label="Dismiss">
                &times;
            </button>
        </div>
    </div>

    <div class="content py-12">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('rounds.create') }}" class="btn btn-green w-full sm:w-56">
                {{ __("Start Thwocking!") }}
            </a>
            <a href="{{ route('courses.create') }}" class="btn btn-yellow w-full sm:w-56">
                {{ __("Add Course") }}
            </a>
        </div>
    </div>
</x-app-layout>

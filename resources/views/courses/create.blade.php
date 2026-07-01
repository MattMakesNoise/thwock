<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-thwock-darker dark:text-thwock-light leading-tight">
            {{ __('Add course') }}
        </h2>
    </x-slot>

    <div class="content py-12">
        <div class="bg-thwock-light dark:bg-thwock-dark overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-thwock-darker dark:text-thwock-light">
                <form method="POST" action="{{ route('courses.store') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="mb-2 block text-sm font-medium text-thwock-dark dark:text-thwock-light">
                            Course name
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker placeholder:text-thwock-dark/60 dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light"
                            required
                            autofocus
                        >
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <h3 class="mb-3 font-medium">Hole pars</h3>

                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                            @for ($hole = 1; $hole <= 18; $hole++)
                                <div>
                                    <label for="hole-{{ $hole }}-par" class="mb-1 block text-sm text-thwock-dark dark:text-thwock-light-muted">
                                        Hole {{ $hole }}
                                    </label>
                                    <input
                                        id="hole-{{ $hole }}-par"
                                        type="number"
                                        name="holes[{{ $hole - 1 }}][par]"
                                        value="{{ old('holes.'.($hole - 1).'.par', 4) }}"
                                        min="3"
                                        max="6"
                                        class="w-full rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light"
                                        required
                                    >
                                    <x-input-error :messages="$errors->get('holes.'.($hole - 1).'.par')" class="mt-1" />
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>{{ __('Save course') }}</x-primary-button>

                        <a href="{{ route('rounds.create') }}" class="text-sm font-medium text-thwock-dark hover:text-thwock-darker dark:text-thwock-light-muted dark:hover:text-thwock-light">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

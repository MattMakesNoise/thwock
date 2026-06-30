<x-app-layout>
    <div class="content py-12">
        <div>
            <div class="bg-thwock-dark overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-thwock-light">
                    <form method="POST" action="{{ route('rounds.store') }}" class="flex flex-col">
                    @csrf

                        <!-- Choose Course -->
                        <label for="course-select" class="floating-label mb-2 text-sm font-medium text-thwock-light">
                            Choose a course:
                        </label>
                        <select name="course_id" id="course-select" class="select select-bordered mb-6 rounded border-thwock-light-muted/30 bg-thwock-darker text-thwock-light">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('course_id')" class="mb-6" />

                        <!-- Add players -->
                        <div id="players-container" class="flex flex-col items-start">
                            <h2 class="font-medium mb-2 text-thwock-light">=== Add players ===</h2>

                            @foreach(old('players', [['display_name' => '', 'email' => '']]) as $index => $player)
                            <div class="player-row flex flex-col items-start" data-index="{{ $index }}">
                                <label class="floating-label mb-2 text-sm font-medium text-thwock-light">
                                    Player Name
                                </label>
                                <input type="text"
                                    name="players[{{ $index }}][display_name]"
                                    value="{{ $player['display_name'] ?? '' }}"
                                    placeholder="Ronald McDonald"
                                    class="input input-bordered mb-6 rounded border-thwock-light-muted/30 bg-thwock-darker text-thwock-light placeholder:text-thwock-light-muted"
                                    required
                                    @if($index === 0) autofocus @endif
                                >
                                <x-input-error :messages="$errors->get('players.'.$index.'.display_name')" class="mb-6" />

                                <label class="floating-label mb-2 text-sm font-medium text-thwock-light">
                                    Player Email
                                </label>
                                <input type="email"
                                    name="players[{{ $index }}][email]"
                                    value="{{ $player['email'] ?? '' }}"
                                    placeholder="ron@mcdonalds.com"
                                    class="input input-bordered mb-6 rounded border-thwock-light-muted/30 bg-thwock-darker text-thwock-light placeholder:text-thwock-light-muted"
                                >
                                <x-input-error :messages="$errors->get('players.'.$index.'.email')" class="mb-6" />
                            </div>
                            @endforeach
                            <x-input-error :messages="$errors->get('players')" class="mb-6" />

                            <div class="form-control mt-8 flex items-center">
                                <button type="button" id="add-player" class="rounded bg-thwock-primary p-3 text-thwock-light hover:bg-thwock-secondary hover:text-thwock-darker">
                                    Add player
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-control mt-8 flex items-center">
                            <button type="submit" class="rounded bg-thwock-light p-3 font-semibold text-thwock-darker hover:bg-thwock-light-muted">
                                Lets Go!
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="content py-12">
        <div>
            <div class="bg-thwock-light dark:bg-thwock-dark overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-thwock-darker dark:text-thwock-light">
                    @if (session('status'))
                        <div class="mb-6 text-sm font-medium text-thwock-primary dark:text-thwock-secondary">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('rounds.store') }}" class="flex flex-col">
                    @csrf

                        <!-- Choose Course -->
                        <label for="course-select" class="floating-label mb-2 text-sm font-medium text-thwock-dark dark:text-thwock-light">
                            Choose a course:
                        </label>
                        <select name="course_id" id="course-select" class="select select-bordered mb-6 rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('courses.create') }}" class="btn btn-yellow mb-6 w-fit">
                            Add a new course
                        </a>
                        <x-input-error :messages="$errors->get('course_id')" class="mb-6" />

                        <div class="mb-8 rounded border border-thwock-dark/25 p-4 dark:border-thwock-light-muted/30">
                            <h2 class="mb-4 font-medium text-thwock-dark dark:text-thwock-light">Your scoring</h2>

                            <div class="mb-6 flex flex-col">
                                <label for="creator_scoring_mode" class="floating-label mb-2 text-sm font-medium text-thwock-dark dark:text-thwock-light">
                                    Scoring Mode
                                </label>
                                <select
                                    id="creator_scoring_mode"
                                    name="creator_scoring_mode"
                                    class="rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light"
                                >
                                    <option value="actual" @selected(old('creator_scoring_mode', 'all_par_4') === 'actual')>Actual course par</option>
                                    <option value="all_par_4" @selected(old('creator_scoring_mode', 'all_par_4') === 'all_par_4')>Every hole par 4</option>
                                    <option value="all_par_5" @selected(old('creator_scoring_mode', 'all_par_4') === 'all_par_5')>Every hole par 5</option>
                                </select>
                                <x-input-error :messages="$errors->get('creator_scoring_mode')" class="mt-2" />
                            </div>

                            <div class="flex flex-col">
                                <label for="creator_handicap_strokes" class="floating-label mb-2 text-sm font-medium text-thwock-dark dark:text-thwock-light">
                                    Handicap Strokes
                                </label>
                                <input
                                    id="creator_handicap_strokes"
                                    type="number"
                                    name="creator_handicap_strokes"
                                    value="{{ old('creator_handicap_strokes', 0) }}"
                                    min="0"
                                    max="54"
                                    class="input input-bordered rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light"
                                >
                                <x-input-error :messages="$errors->get('creator_handicap_strokes')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Add players -->
                        <div id="players-container" class="flex flex-col items-start">
                            <h2 class="font-medium mb-2 text-thwock-dark dark:text-thwock-light">=== Add other players ===</h2>

                            @foreach(old('players', [['display_name' => '', 'email' => '', 'scoring_mode' => 'all_par_4', 'handicap_strokes' => 0]]) as $index => $player)
                            <div class="player-row flex flex-col items-start" data-index="{{ $index }}">
                                <label class="floating-label mb-2 text-sm font-medium text-thwock-dark dark:text-thwock-light">
                                    Player Name
                                </label>
                                <input type="text"
                                    name="players[{{ $index }}][display_name]"
                                    value="{{ $player['display_name'] ?? '' }}"
                                    placeholder="Ronald McDonald"
                                    class="input input-bordered mb-6 rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker placeholder:text-thwock-dark/60 dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light dark:placeholder:text-thwock-light-muted"
                                    required
                                    @if($index === 0) autofocus @endif
                                >
                                <x-input-error :messages="$errors->get('players.'.$index.'.display_name')" class="mb-6" />

                                <label class="floating-label mb-2 text-sm font-medium text-thwock-dark dark:text-thwock-light">
                                    Player Email
                                </label>
                                <input type="email"
                                    name="players[{{ $index }}][email]"
                                    value="{{ $player['email'] ?? '' }}"
                                    placeholder="ron@mcdonalds.com"
                                    class="input input-bordered mb-6 rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker placeholder:text-thwock-dark/60 dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light dark:placeholder:text-thwock-light-muted"
                                >
                                <x-input-error :messages="$errors->get('players.'.$index.'.email')" class="mb-6" />

                                <div class="mb-6 flex flex-col">
                                    <label class="floating-label mb-2 text-sm font-medium text-thwock-dark dark:text-thwock-light">
                                        Scoring Mode
                                    </label>
                                    <select
                                        name="players[{{ $index }}][scoring_mode]"
                                        class="rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light"
                                    >
                                        <option value="actual" @selected(($player['scoring_mode'] ?? 'all_par_4') === 'actual')>Actual course par</option>
                                        <option value="all_par_4" @selected(($player['scoring_mode'] ?? 'all_par_4') === 'all_par_4')>Every hole par 4</option>
                                        <option value="all_par_5" @selected(($player['scoring_mode'] ?? 'all_par_4') === 'all_par_5')>Every hole par 5</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('players.'.$index.'.scoring_mode')" class="mt-2" />
                                </div>

                                <div class="mb-6 flex flex-col">
                                    <label class="floating-label mb-2 text-sm font-medium text-thwock-dark dark:text-thwock-light">
                                        Handicap Strokes
                                    </label>
                                    <input type="number"
                                        name="players[{{ $index }}][handicap_strokes]"
                                        value="{{ $player['handicap_strokes'] ?? 0 }}"
                                        min="0"
                                        max="54"
                                        class="input input-bordered rounded border-thwock-dark/25 bg-thwock-light-muted text-thwock-darker dark:border-thwock-light-muted/30 dark:bg-thwock-darker dark:text-thwock-light"
                                    >
                                    <x-input-error :messages="$errors->get('players.'.$index.'.handicap_strokes')" class="mt-2" />
                                </div>
                            </div>
                            @endforeach
                            <x-input-error :messages="$errors->get('players')" class="mb-6" />

                            <div class="form-control mt-8 flex items-center">
                                <button type="button" id="add-player" class="btn btn-yellow">
                                    Add player
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-control mt-8 flex items-center">
                            <button type="submit" class="rounded bg-thwock-primary p-3 font-semibold text-thwock-light hover:bg-thwock-primary/80">
                                Lets Go!
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

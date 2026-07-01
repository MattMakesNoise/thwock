<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-thwock-darker dark:text-thwock-light leading-tight">
            {{ __('Current round') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-thwock-light dark:bg-thwock-dark overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-thwock-darker dark:text-thwock-light sm:p-6">
                    @if (session('status'))
                        <div class="mb-6 text-sm font-medium text-thwock-primary dark:text-thwock-secondary">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-6 flex flex-col gap-1">
                        <p class="text-sm text-thwock-dark dark:text-thwock-light-muted">{{ $round->course->name }}</p>
                        <h3 class="text-2xl font-semibold">{{ __('Score round') }}</h3>
                        <p class="text-sm text-thwock-dark dark:text-thwock-light-muted">
                            {{ $round->scorecards->count() }} {{ Str::plural('scorecard', $round->scorecards->count()) }}
                        </p>
                    </div>

                    @if (! $scorecard)
                        <div class="rounded border border-thwock-light-muted p-4 dark:border-thwock-light-muted/20">
                            <p class="mb-4 text-sm text-thwock-dark dark:text-thwock-light-muted">
                                {{ __('Join this round to keep your own independent scorecard. Your scores will not overwrite anyone else’s.') }}
                            </p>

                            <form method="POST" action="{{ route('rounds.scorecards.store', $round) }}">
                                @csrf

                                <x-primary-button>{{ __('Join as scorer') }}</x-primary-button>
                            </form>
                        </div>
                    @else
                        <div class="mb-6 rounded border border-thwock-light-muted p-4 dark:border-thwock-light-muted/20">
                            <p class="text-sm text-thwock-dark dark:text-thwock-light-muted">{{ __('Your scorecard') }}</p>
                            <p class="font-semibold">{{ $scorecard->name }}</p>
                        </div>

                        @if ($round->finalScores->isNotEmpty())
                            <div class="mb-6 rounded border border-thwock-primary bg-thwock-light-muted p-4 text-thwock-darker dark:border-thwock-primary dark:bg-thwock-darker dark:text-thwock-light">
                                <h4 class="mb-3 font-semibold">{{ __('Final scores recorded') }}</h4>

                                <div class="space-y-2">
                                    @foreach ($round->players as $player)
                                        <div class="flex items-center justify-between gap-3">
                                            <span>{{ $player->display_name }}</span>
                                            <span class="font-semibold">{{ $round->finalScores->where('round_player_id', $player->id)->sum('strokes') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($round->user_id === auth()->id())
                            <form method="POST" action="{{ route('rounds.final-scores.store', $round) }}" class="mb-6 rounded border border-thwock-light-muted p-4 dark:border-thwock-light-muted/20">
                                @csrf

                                <label for="scorecard_id" class="mb-2 block text-sm font-medium text-thwock-dark dark:text-thwock-light-muted">
                                    {{ __('Agreed scorecard') }}
                                </label>
                                <select id="scorecard_id" name="scorecard_id" class="mb-4 block w-full rounded border-thwock-dark/25 dark:border-thwock-light-muted/20 dark:bg-thwock-darker dark:text-thwock-light">
                                    @foreach ($round->scorecards as $roundScorecard)
                                        <option value="{{ $roundScorecard->id }}" @selected($roundScorecard->id === $scorecard->id)>
                                            {{ $roundScorecard->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <x-primary-button>{{ __('Record final scores') }}</x-primary-button>
                            </form>
                        @endif

                        <div class="mb-6 flex gap-2 overflow-x-auto pb-2">
                            @foreach ($round->course->holes as $hole)
                                <a href="#hole-{{ $hole->hole_number }}" class="flex h-11 min-w-11 items-center justify-center rounded border border-thwock-dark/25 text-sm font-semibold dark:border-thwock-light-muted/30">
                                    {{ $hole->hole_number }}
                                </a>
                            @endforeach
                        </div>

                        <div data-score-board class="space-y-8">
                            @foreach ($round->course->holes as $hole)
                                <section id="hole-{{ $hole->hole_number }}" class="scroll-mt-4 border-t border-thwock-light-muted pt-6 dark:border-thwock-light-muted/20">
                                    <div class="mb-4 flex items-baseline justify-between">
                                        <h4 class="text-xl font-semibold">{{ __('Hole') }} {{ $hole->hole_number }}</h4>
                                        <span class="text-sm text-thwock-dark dark:text-thwock-light-muted">{{ __('Par') }} {{ $hole->par }}</span>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach ($round->players as $player)
                                            @php
                                                $score = $scorecard->scores->first(fn ($score) => $score->round_player_id === $player->id && $score->hole_number === $hole->hole_number);
                                                $targetPar = $player->targetParForHole($hole);
                                                $scoreVsTarget = $score->strokes - $targetPar;
                                                $scoreVsActual = $score->strokes - $hole->par;
                                            @endphp

                                            <div
                                                class="grid grid-cols-[1fr_auto] items-center gap-3 rounded border border-thwock-light-muted p-3 dark:border-thwock-light-muted/20"
                                                data-score-row
                                                data-score-url="{{ route('rounds.scores.update', [$round, $score]) }}"
                                                data-target-par="{{ $targetPar }}"
                                                data-actual-par="{{ $hole->par }}"
                                            >
                                                <div class="min-w-0">
                                                    <p class="truncate font-medium">{{ $player->display_name }}</p>
                                                    <p class="text-sm text-thwock-dark dark:text-thwock-light-muted">
                                                        Target {{ $targetPar }}
                                                        @if ($player->handicap_strokes > 0)
                                                            · Handicap {{ $player->handicap_strokes }}
                                                        @endif
                                                    </p>
                                                    <p class="text-sm font-medium text-thwock-primary dark:text-thwock-secondary">
                                                        <span data-score-vs-target>{{ $scoreVsTarget > 0 ? '+' : '' }}{{ $scoreVsTarget }}</span> vs target
                                                        @if ($player->scoring_mode !== 'actual')
                                                            · <span data-score-vs-actual>{{ $scoreVsActual > 0 ? '+' : '' }}{{ $scoreVsActual }}</span> vs actual
                                                        @endif
                                                    </p>
                                                    <p class="hidden text-sm text-red-600" data-score-error>{{ __('Could not save. Try again.') }}</p>
                                                </div>

                                                <div class="grid grid-cols-[3.25rem_3.25rem_3.25rem] items-center gap-2">
                                                    <button type="button" class="h-14 rounded bg-thwock-light-muted text-2xl font-semibold text-thwock-darker dark:bg-thwock-dark dark:text-thwock-light" data-score-adjust="-1" aria-label="Subtract stroke for {{ $player->display_name }} on hole {{ $hole->hole_number }}">
                                                        -
                                                    </button>
                                                    <div class="flex h-14 items-center justify-center rounded bg-thwock-darker text-2xl font-semibold text-thwock-light dark:bg-thwock-light-muted dark:text-thwock-darker" data-score-value>
                                                        {{ $score->strokes }}
                                                    </div>
                                                    <button type="button" class="h-14 rounded bg-thwock-light-muted text-2xl font-semibold text-thwock-darker dark:bg-thwock-dark dark:text-thwock-light" data-score-adjust="1" aria-label="Add stroke for {{ $player->display_name }} on hole {{ $hole->hole_number }}">
                                                        +
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FinalHoleScore;
use App\Models\HoleScore;
use App\Models\Round;
use App\Models\Scorecard;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StartController extends Controller
{
    public function index()
    {

    }

    public function create(): View
    {
        $courses = Course::all();

        return view('rounds.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'players' => ['required', 'array', 'min:1'],
            'players.*.display_name' => ['required', 'string', 'max:255'],
            'players.*.email' => ['nullable', 'email', 'max:255'],
        ]);

        $round = DB::transaction(function () use ($validated, $request) {
            $round = Round::create([
                'course_id' => $validated['course_id'],
                'user_id' => $request->user()->id,
                'status' => 'active',
            ]);

            $course = Course::with('holes')->findOrFail($validated['course_id']);

            foreach (array_values($validated['players']) as $index => $player) {
                $round->players()->create([
                    'display_name' => $player['display_name'],
                    'email' => $player['email'] ?? null,
                    'position' => $index + 1,
                ]);
            }

            $this->createScorecard($round, $request->user(), $course);

            return $round;
        });

        return redirect()
            ->route('rounds.show', $round)
            ->with('status', 'Round created.');
    }

    public function show(Request $request, Round $round): View
    {
        $round->load([
            'course.holes' => fn ($query) => $query->orderBy('hole_number'),
            'players' => fn ($query) => $query->orderBy('position'),
        ]);

        $scorecard = $round->scorecards()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($round->user_id === $request->user()->id && ! $scorecard) {
            $scorecard = DB::transaction(fn () => $this->createScorecard($round, $request->user(), $round->course));
        }

        $round->load([
            'course.holes' => fn ($query) => $query->orderBy('hole_number'),
            'players' => fn ($query) => $query->orderBy('position'),
            'scorecards.user',
            'finalScores',
        ]);

        if ($scorecard) {
            $scorecard->load('scores');
        }

        return view('rounds.show', compact('round', 'scorecard'));
    }

    public function updateScore(Request $request, Round $round, HoleScore $holeScore): Response
    {
        abort_unless($holeScore->round_id === $round->id, 404);
        abort_unless($holeScore->scorecard?->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'strokes' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $holeScore->update([
            'strokes' => $validated['strokes'],
        ]);

        return response()->noContent();
    }

    public function joinScorecard(Request $request, Round $round): RedirectResponse
    {
        $round->load(['course.holes', 'players']);

        DB::transaction(function () use ($round, $request) {
            $round->scorecards()->where('user_id', $request->user()->id)->first()
                ?? $this->createScorecard($round, $request->user(), $round->course);
        });

        return redirect()
            ->route('rounds.show', $round)
            ->with('status', 'You are scoring this round independently.');
    }

    public function storeFinalScores(Request $request, Round $round): RedirectResponse
    {
        abort_unless($round->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'scorecard_id' => ['required', 'integer', 'exists:scorecards,id'],
        ]);

        $scorecard = $round->scorecards()
            ->with('scores')
            ->whereKey($validated['scorecard_id'])
            ->firstOrFail();

        DB::transaction(function () use ($round, $scorecard) {
            $round->finalScores()->delete();

            foreach ($scorecard->scores as $score) {
                $round->finalScores()->create([
                    'round_player_id' => $score->round_player_id,
                    'hole_number' => $score->hole_number,
                    'strokes' => $score->strokes,
                ]);
            }

            $round->update([
                'status' => 'final',
            ]);
        });

        return redirect()
            ->route('rounds.show', $round)
            ->with('status', 'Final scores recorded.');
    }

    private function createScorecard(Round $round, User $user, Course $course): Scorecard
    {
        $scorecard = $round->scorecards()->create([
            'user_id' => $user->id,
            'name' => $user->name,
        ]);

        foreach ($round->players as $roundPlayer) {
            foreach ($course->holes as $hole) {
                $scorecard->scores()->create([
                    'round_id' => $round->id,
                    'round_player_id' => $roundPlayer->id,
                    'hole_number' => $hole->hole_number,
                    'strokes' => 4,
                ]);
            }
        }

        return $scorecard;
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}

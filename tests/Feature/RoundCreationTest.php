<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\FinalHoleScore;
use App\Models\HoleScore;
use App\Models\Round;
use App\Models\Scorecard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoundCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_round_with_players(): void
    {
        $user = User::factory()->create();
        $course = Course::create(['name' => 'Mousehold']);
        $course->holes()->createMany([
            ['hole_number' => 1, 'par' => 4],
            ['hole_number' => 2, 'par' => 3],
        ]);

        $response = $this->actingAs($user)->post(route('rounds.store'), [
            'course_id' => $course->id,
            'players' => [
                [
                    'display_name' => 'Matt',
                    'email' => 'matt@example.com',
                ],
                [
                    'display_name' => 'Sam',
                    'email' => null,
                ],
            ],
        ]);

        $round = Round::first();

        $response->assertRedirect(route('rounds.show', $round, absolute: false));

        $this->assertDatabaseHas('rounds', [
            'id' => $round->id,
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('round_players', [
            'round_id' => $round->id,
            'display_name' => 'Matt',
            'email' => 'matt@example.com',
            'position' => 1,
        ]);

        $this->assertDatabaseHas('round_players', [
            'round_id' => $round->id,
            'display_name' => 'Sam',
            'email' => null,
            'position' => 2,
        ]);

        $this->assertDatabaseCount('hole_scores', 4);
        $this->assertDatabaseCount('scorecards', 1);
        $this->assertSame([4], HoleScore::query()->pluck('strokes')->unique()->values()->all());
    }

    public function test_round_requires_at_least_one_player(): void
    {
        $user = User::factory()->create();
        $course = Course::create(['name' => 'Mousehold']);

        $response = $this->actingAs($user)->post(route('rounds.store'), [
            'course_id' => $course->id,
            'players' => [],
        ]);

        $response->assertSessionHasErrors('players');
        $this->assertDatabaseCount('rounds', 0);
    }

    public function test_user_can_update_a_score_for_their_round(): void
    {
        $user = User::factory()->create();
        $course = Course::create(['name' => 'Mousehold']);
        $course->holes()->create(['hole_number' => 1, 'par' => 4]);

        $this->actingAs($user)->post(route('rounds.store'), [
            'course_id' => $course->id,
            'players' => [
                ['display_name' => 'Matt', 'email' => null],
            ],
        ]);

        $round = Round::first();
        $score = HoleScore::first();

        $response = $this->actingAs($user)->patchJson(route('rounds.scores.update', [$round, $score]), [
            'strokes' => 5,
        ]);

        $response->assertNoContent();
        $this->assertDatabaseHas('hole_scores', [
            'id' => $score->id,
            'strokes' => 5,
        ]);
    }

    public function test_second_scorer_can_keep_an_independent_scorecard(): void
    {
        $owner = User::factory()->create(['name' => 'Owner']);
        $secondScorer = User::factory()->create(['name' => 'Second Scorer']);
        $course = Course::create(['name' => 'Mousehold']);
        $course->holes()->create(['hole_number' => 1, 'par' => 4]);

        $this->actingAs($owner)->post(route('rounds.store'), [
            'course_id' => $course->id,
            'players' => [
                ['display_name' => 'Matt', 'email' => null],
            ],
        ]);

        $round = Round::first();

        $response = $this->actingAs($secondScorer)->post(route('rounds.scorecards.store', $round));

        $response->assertRedirect(route('rounds.show', $round, absolute: false));
        $this->assertDatabaseCount('scorecards', 2);
        $this->assertDatabaseCount('hole_scores', 2);

        $ownerScore = Scorecard::where('user_id', $owner->id)->first()->scores()->first();
        $secondScore = Scorecard::where('user_id', $secondScorer->id)->first()->scores()->first();

        $this->actingAs($secondScorer)->patchJson(route('rounds.scores.update', [$round, $secondScore]), [
            'strokes' => 5,
        ])->assertNoContent();

        $this->assertDatabaseHas('hole_scores', [
            'id' => $ownerScore->id,
            'strokes' => 4,
        ]);
        $this->assertDatabaseHas('hole_scores', [
            'id' => $secondScore->id,
            'strokes' => 5,
        ]);
    }

    public function test_round_owner_can_record_final_scores_from_a_scorecard(): void
    {
        $owner = User::factory()->create();
        $course = Course::create(['name' => 'Mousehold']);
        $course->holes()->createMany([
            ['hole_number' => 1, 'par' => 4],
            ['hole_number' => 2, 'par' => 4],
        ]);

        $this->actingAs($owner)->post(route('rounds.store'), [
            'course_id' => $course->id,
            'players' => [
                ['display_name' => 'Matt', 'email' => null],
            ],
        ]);

        $round = Round::first();
        $scorecard = Scorecard::first();

        $scorecard->scores()->where('hole_number', 1)->update(['strokes' => 3]);
        $scorecard->scores()->where('hole_number', 2)->update(['strokes' => 5]);

        $response = $this->actingAs($owner)->post(route('rounds.final-scores.store', $round), [
            'scorecard_id' => $scorecard->id,
        ]);

        $response->assertRedirect(route('rounds.show', $round, absolute: false));
        $this->assertDatabaseCount('final_hole_scores', 2);
        $this->assertSame(8, FinalHoleScore::sum('strokes'));
        $this->assertDatabaseHas('rounds', [
            'id' => $round->id,
            'status' => 'final',
        ]);
    }

    public function test_round_show_displays_default_scores(): void
    {
        $user = User::factory()->create();
        $course = Course::create(['name' => 'Mousehold']);
        $course->holes()->create(['hole_number' => 1, 'par' => 4]);

        $this->actingAs($user)->post(route('rounds.store'), [
            'course_id' => $course->id,
            'players' => [
                ['display_name' => 'Matt', 'email' => null],
            ],
        ]);

        $round = Round::first();

        $response = $this->actingAs($user)->get(route('rounds.show', $round));

        $response
            ->assertOk()
            ->assertSee('Hole 1')
            ->assertSee('Matt')
            ->assertSee('4');
    }
}

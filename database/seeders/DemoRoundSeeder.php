<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Round;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoRoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mattScores = [4, 6, 5, 4, 5, 4, 6, 7, 5, 5, 5, 4, 4, 4, 5, 6, 6, 4];
        $chrisScores = [4, 4, 3, 4, 4, 4, 3, 4, 4, 4, 4, 4, 3, 4, 4, 4, 4, 4];

        DB::transaction(function () use ($mattScores, $chrisScores) {
            $course = Course::where('name', 'Mousehold')->firstOrFail();

            $matt = User::firstOrCreate(
                ['email' => 'matt@example.com'],
                [
                    'name' => 'Matt',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );

            $this->deleteExistingDemoRounds($matt, $course);

            $round = Round::create([
                'course_id' => $course->id,
                'user_id' => $matt->id,
                'status' => 'final',
            ]);

            $mattPlayer = $round->players()->create([
                'display_name' => 'Matt',
                'email' => 'matt@example.com',
                'user_id' => $matt->id,
                'position' => 1,
                'scoring_mode' => 'all_par_4',
                'handicap_strokes' => 1,
            ]);

            $chrisPlayer = $round->players()->create([
                'display_name' => 'Chris',
                'email' => 'chris@example.com',
                'position' => 2,
                'scoring_mode' => 'all_par_4',
                'handicap_strokes' => 0,
            ]);

            $scorecard = $round->scorecards()->create([
                'user_id' => $matt->id,
                'name' => 'Seeded demo scorecard',
            ]);

            foreach ($mattScores as $index => $strokes) {
                $holeNumber = $index + 1;

                $scorecard->scores()->create([
                    'round_id' => $round->id,
                    'round_player_id' => $mattPlayer->id,
                    'hole_number' => $holeNumber,
                    'strokes' => $strokes,
                ]);

                $round->finalScores()->create([
                    'round_player_id' => $mattPlayer->id,
                    'hole_number' => $holeNumber,
                    'strokes' => $strokes,
                ]);
            }

            foreach ($chrisScores as $index => $strokes) {
                $holeNumber = $index + 1;

                $scorecard->scores()->create([
                    'round_id' => $round->id,
                    'round_player_id' => $chrisPlayer->id,
                    'hole_number' => $holeNumber,
                    'strokes' => $strokes,
                ]);

                $round->finalScores()->create([
                    'round_player_id' => $chrisPlayer->id,
                    'hole_number' => $holeNumber,
                    'strokes' => $strokes,
                ]);
            }
        });
    }

    private function deleteExistingDemoRounds(User $user, Course $course): void
    {
        $rounds = Round::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereHas('scorecards', fn ($query) => $query->where('name', 'Seeded demo scorecard'))
            ->get();

        foreach ($rounds as $round) {
            $round->finalScores()->delete();
            $round->scores()->delete();
            $round->scorecards()->delete();
            $round->players()->delete();
            $round->delete();
        }
    }
}

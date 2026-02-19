<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Hole;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pars = [4,3,3,3,3,3,3,4,3,3,4,3,3,3,4,3,3,4];

        $course = Course::firstOrCreate([
            'name' => 'Mousehold',
        ]);

        // Make it safe to re-run.
        $course->holes()->delete();

        $holes = [];
        foreach ($pars as $index => $par) {
            $holes[] = [
                'hole_number' => $index + 1,
                'par' => $par,
            ];
        }

        $course->holes()->createMany($holes);
    }
}

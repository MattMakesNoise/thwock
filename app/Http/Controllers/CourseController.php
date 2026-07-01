<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function create(): View
    {
        return view('courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:courses,name'],
            'holes' => ['required', 'array', 'size:18'],
            'holes.*.par' => ['required', 'integer', 'min:3', 'max:6'],
        ]);

        $course = DB::transaction(function () use ($validated) {
            $course = Course::create([
                'name' => $validated['name'],
            ]);

            foreach (array_values($validated['holes']) as $index => $hole) {
                $course->holes()->create([
                    'hole_number' => $index + 1,
                    'par' => $hole['par'],
                ]);
            }

            return $course;
        });

        return redirect()
            ->route('rounds.create')
            ->with('status', "{$course->name} created.");
    }
}

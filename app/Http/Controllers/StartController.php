<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class StartController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        $courses = Course::all();

        return view('rounds.create', compact('courses'));
    }

    public function store(Request $request)
    {
        // Save the round against the course.
    }

    public function show($id)
    {

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exercise;

class ExerciseController extends Controller
{
    public function get(Request $request)
    {
        $exercises = Exercise::all();
        $exercises = $exercises->map(function($exercise){
            unset($exercise->id);
            unset($exercise->bulk_id);
            return $exercise;
        });
        return view('exercises', compact('exercises') );
    }
}

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
    
    public function get_by_id(Request $request, $id)
    {
        $exercise = Exercise::find($id);
        if($exercise == null)
            return abort(404);

        $blob = $exercise->live_data;
        $blob_names = ["calorie", "distance", "speed"];
        $blob_array =  json_decode(gzdecode($blob), true);
        for($i = 0; $i < count($blob_array); $i++)
        {
            $blob_array[$i] = array_intersect_key($blob_array[$i], array_flip($blob_names));
        }
        $blob = json_encode($blob_array);
        
        unset($exercise->id);
        unset($exercise->bulk_id);
        $exercise = '['.$exercise->toJson().']'; //TODO think about it, this line must not be here

        return view('exercise', compact('exercise', 'id', 'blob') );
    }
}

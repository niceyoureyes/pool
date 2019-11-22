<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Exercise;

use DateTime;

class ExerciseController extends Controller
{
    public function get(Request $request)
    {
        $exercises = Exercise::all();
        $exercises = $exercises->map(function($exercise){
            $date_end = new DateTime($exercise->end_time);
            $date_start = new DateTime($exercise->start_time);
            $date_diff = $date_end->diff($date_start);
            $exercise->end_time = $date_diff->format("%I:%S"); // this is full time
            $exercise->link_id = '<a href="'.route('get_exercise_by_id', ['id' => $exercise->id]).'">Подробно</a>';
            return $exercise;
        });

        $names = "['#','Начало', 'Общая длительность', 'Длительность', 'Дистанция', 'Средний темп', 'Максимальный темп', 'Комментарий', 'Подробно']";

        $formats = ['dt' => 'Y:M:D', 'i' => '%I:%S', 'float' => '2' ]; //format interface!!!
        $filters = ['ad', 'eq', 'vals', 'custom'];

        return view('exercises', compact('exercises', 'names') );
    }
    
    public function get_by_id(Request $request, $id)
    {
        Log::info("***** Exercise Controller ***** get_by_id start *****");
        $exercise = Exercise::find($id);
        if($exercise == null)
            return abort(404);

        //live data blob
        $date = new DateTime();
        $live_data_string = null;
        if($exercise->live_data != null)
        {
            $live_data_string = gzdecode($exercise->live_data);
            Log::info("Live data input (json string):\n".$live_data_string);
            $live_data_names = ["start_time", "heart_rate"];
            $live_data_array =  json_decode($live_data_string, true);
            for($i = 0; $i < count($live_data_array); $i++)
            {
                $live_data_array[$i] = array_intersect_key($live_data_array[$i], array_flip($live_data_names));
                $date->setTimestamp($live_data_array[$i]['start_time'] / 1000);
                $live_data_array[$i]['start_time'] = $date->format('Y-m-d H:i:s');
            }
            $live_data_string = json_encode($live_data_array);
            Log::info("Live data output (json string):\n".$live_data_string);
        }

        //addl data blob
        $addl_data_string = null;
        if($exercise->addl_data != null)
        {
            $addl_data_string = gzdecode($exercise->addl_data);
            Log::info("Addl data input (json string):\n".$addl_data_string);
            $addl_data_names  = ["duration", "interval", "stroke_count", "stroke_type"];
            $addl_data_array  =  json_decode($addl_data_string, true);
            $data             = $addl_data_array['lengths'];
            $pool_length      = $addl_data_array['pool_length'];
            $pool_length_unit = $addl_data_array['pool_length_unit'];
            $total_distance   = $addl_data_array['total_distance'];
            $total_duration   = $addl_data_array['total_duration'] / 1000;
            for($i = 0; $i < count($data); $i++)
            {
                $data[$i] = array_intersect_key($data[$i], array_flip($addl_data_names));
                $data[$i]['duration'] = $data[$i]['duration'] / 1000;
            }
            $addl_data_string = json_encode($data);
            Log::info("Addl data output (json string):\n".$addl_data_string);
        }
        
        $date_end = new DateTime($exercise->end_time);
        $date_start = new DateTime($exercise->start_time);
        $date_diff = $date_end->diff($date_start);
        $exercise->end_time = $date_diff->format("%I:%S"); // this is full time
        $exercise = '['.$exercise->toJson().']'; //TODO think about it, this line must not be here
        $names = "['#','Начало', 'Общая длительность', 'Длительность', 'Дистанция', 'Средний темп', 'Максимальный темп', 'Комментарий']";

        Log::info("***** Exercise Controller ***** get_by_id end *****\n");

        return view('exercise', compact('id', 'exercise', 'names', 'live_data_string', 'addl_data_string',
                                        'pool_length', 'pool_length_unit', 'total_distance', 'total_duration') );
    }
}

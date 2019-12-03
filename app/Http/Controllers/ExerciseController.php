<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Exercise;
use App\Handler;

use DateTime;
use DateInterval;

class ExerciseController extends Controller
{
    private $data_from      =  [ 'id', 'start_time'  , 'end_time'          , 'duration'    , 'max_tempo'        , 'mean_tempo'  , 'stroke_count'      , 'swolf'     , 'comment'    , 'length_type', 'distance'             ] ;
    private $data_to        =  [       'start_time'  , 'total_time'        , 'duration'    , 'max_tempo'        , 'mean_tempo'  , 'stroke_count'      , 'swolf'     , 'comment'    , 'length_type', 'distance' , 'link_id' ] ;
    private $formats        =  [       ['dt','d-m-Y'], ['i','%I:%S']       , ['i','%I:%S'] , ['float',1]        , ['float',1]   ,  null               , ['float',1] ,  null        ,  null        ,  null      ,  null     ] ;
    private $filters        =  [       'asc_desc'    , 'null'              , 'asc_desc'    , 'asc_desc'         , 'asc_desc'    , 'asc_desc'          , 'asc_desc'  , 'custom1'    , 'vals'       , 'vals'     , 'null'    ] ;
    private $names          = "[ '#' , 'Дата'        , 'Общая длительность', 'Длительность', 'Максимальный темп', 'Средний темп', 'Количество гребков', 'Swolf'     , 'Комментарий', 'Тип заплыва', 'Дистанция', 'Подробно']";

    // ***** Handlers *****
    public function get(Request $request)
    {
        $exercises = Exercise::all()->toArray();
        $exercises = $this->resolveExercise($this->data_from, $this->data_to, $this->formats, $exercises);
        $names = $this->names;
        $filters = $this->filters;
        return view('exercises', compact('exercises', 'names', 'filters') );
    }
    
    public function get_by_id(Request $request, $id)
    {
        Log::info("***** Exercise Controller ***** get_by_id start *****");
        $exercise = Exercise::find($id);
        if($exercise == null)
            return abort(404);

        /*/live data blob
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
        }*/

        //addl data blob
        $addl_data_string = null;
        if($exercise->addl_data != null)
        {
            $addl_data_string = gzdecode($exercise->addl_data);
            Log::info("Addl data input (json string):\n".$addl_data_string);
            $addl_data_array  =  json_decode($addl_data_string, true);
            $data             = $addl_data_array['lengths'];
            $pool_length      = $addl_data_array['pool_length'];
            $pool_length_unit = $addl_data_array['pool_length_unit'];
            $total_distance   = $addl_data_array['total_distance'];
            $total_duration   = $addl_data_array['total_duration'] / 1000;
            $addl_data_string = $this->resolveAddl($data);
            Log::info("Addl data output (json string):\n".$addl_data_string);
        }
        
        //exercise
        $exercises   = [];
        $exercises[] = $exercise->toArray();
        $exercise    = $this->resolveExercise($this->data_from, $this->data_to, $this->formats, $exercises);
        $names       = $this->names;

        Log::info("***** Exercise Controller ***** get_by_id end *****\n");

        //Do not use live_data_string (by AH)
        $live_data_string = null;

        return view('exercise', compact('id', 'exercise', 'names', 'live_data_string', 'addl_data_string',
                                        'pool_length', 'pool_length_unit', 'total_distance', 'total_duration') );
    }

    // ***** Functions *****
    public function resolveExercise($data_from, $data_to, $formats, $exercises)
    {
        $hnd = new Handler();

        $handlers = [
            'total_time' => function($x, $d)
            {
                $date_end = new DateTime($d['end_time']);
                $date_start = new DateTime($d['start_time']);
                return $date_end->diff($date_start);
            },
            'link_id'    => function($x, $d)
            {
                return '<a href="'.route('get_exercise_by_id', ['id' => $d['id']] ).'">Подробно</a>';
            },
            'duration' => function($x, $d)
            {
                return new DateInterval('PT'.floor(round($x) / 60).'M'.(round($x) % 60).'S');
            }
        ];

        $exercises = $hnd->resolveFields($data_from, $data_to, $handlers, $formats, $exercises);
        return json_encode($exercises);
    }

    public function resolveAddl($data)
    {
        $hnd = new Handler();

        $data_from  =  [ 'duration'    , 'interval', 'stroke_count'      , 'stroke_type' ] ;
        $data_to    =  [ 'duration'    , 'interval', 'stroke_count'      , 'stroke_type' ] ;
        $formats    =  [ ["i","%I:%S"] ,  null     ,  null               ,  null         ] ;

        $handlers = [
            'duration' => function($x){
                $x = $x / 1000;
                return new DateInterval('PT'.floor(round($x) / 60).'M'.(round($x) % 60).'S');
            }
        ];

        $data = $hnd->resolveFields($data_from, $data_to, $handlers, $formats, $data);

        return json_encode($data);
    }
}

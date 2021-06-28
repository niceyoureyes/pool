<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Exercise;
use App\Table;
use App\Bulk;

use DateTime;
use DateInterval;
use Auth;

class ExerciseController extends Controller
{
    private $data_from      =  [ 'id'  , 'start_time'                        , 'end_time'          , 'duration'                          , 'max_tempo'                         , 'mean_tempo'                           , 'stroke_count'                               , 'swolf'                              , 'comment'    , 'length_type', 'distance'                             ] ;
    private $data_to        =  [         'start_time'                        , 'total_time'        , 'duration'                          , 'max_tempo'                         , 'mean_tempo'                           , 'stroke_count'                               , 'swolf'                              , 'comment'    , 'length_type', 'distance'                 , 'link_id' ] ;
    private $data_to_2      =  [         'start_time'                        , 'total_time'        , 'duration'                          , 'max_tempo'                         , 'mean_tempo'                           , 'stroke_count'                               , 'swolf'                              , 'comment'    , 'length_type', 'distance'                             ] ;
    private $formats        =  [         ['dt','d-m-Y']                      , ['i','%I:%S']       , ['i','%I:%S']                       , ['i','%I:%S']                       , ['i','%I:%S']                          ,  null                                        , ['float',1]                          ,  null        ,  null        ,  null                      ,  null     ] ;
    private $formats_graph  =  [         ['dt','d-m-Y']                      , ['float',0]         ,  null                               , null                                , null                                   ,  null                                        , ['float',1]                          ,  null        ,  null        ,  null                      ,  null     ] ;
    private $filters        =  [  null , ['start_time', 'asc_desc', null]    ,  null               , ['duration', 'asc_desc', null]      , ['max_tempo', 'asc_desc', null]     , ['mean_tempo', 'asc_desc', null]       , ['stroke_count', 'asc_desc', null]           , ['swolf', 'asc_desc', null]          ,  null        ,  null        , ['distance', 'vals', null] ,  null     ] ;
    private $names          =  [ '#'   , 'Дата'                              , 'Общая длительность', 'Длительность'                      , 'Максимальный темп'                 , 'Средний темп'                         , 'Количество гребков'                         , 'Swolf'                              , 'Комментарий', 'Тип заплыва', 'Дистанция'                , 'Подробно'] ;
    private $names_2        =  [ '#'   , 'Дата'                              , 'Общая длительность', 'Длительность'                      , 'Максимальный темп'                 , 'Средний темп'                         , 'Количество гребков'                         , 'Swolf'                              , 'Комментарий', 'Тип заплыва', 'Дистанция'                            ] ;

    // ***** Handlers *****
    public function get(Request $request)
    {
        /* Redirect to Graph */
        if( $request->has('newfilter') && $request->has('xy') && $request->has('filters') && $request->input('newfilter') == 0 )
        {
            $type = 1;
            $xy = json_decode($request->input('xy'));
            $filters = json_decode($request->input('filters'), true);
            $exercises = Table::filter(new Exercise, $filters);
            $exercises = $exercises->get();
            $exercises = $this->resolveExercise($this->data_from, $this->data_to, $this->formats_graph, $exercises);
            $names = $this->names;

            $label_x = $names[$xy[0] - 1];
            $label_y = $names[$xy[1] - 1];
            if($label_x == "Дата") $type = 2;

            $exercises = json_decode($exercises, true); // enc + dec!!!
            $input_data = [];
            for($i = 0; $i < count($exercises); $i++)
            {
                $x = $exercises[$i][ $this->data_to[$xy[0] - 2]];
                $y = $exercises[$i][ $this->data_to[$xy[1] - 2]] * 1;
                if($type == 1)
                {
                    $x = $x * 1;
                }
                $input_data[] = ['x' => $x, 'y' => $y];
            }

            return redirect()->route('graph', ['label_x' => $label_x, 'label_y' => $label_y, 'input_data' => $input_data, 'type' => $type]);
        }

        /* Creating filter. Redirect to youself */
        if( $request->has('newfilter') && $request->has('indexes') && $request->has('filters'))
        {
            $n = $request->input('newfilter');
            $filters = json_decode($request->input('filters'), true);
            $indexes = json_decode($request->input('indexes'), true);
            $newfilter = $this->filters[$n];
            $newfilter[] = $n;

            Table::create_filter(new Exercise, $filters, $indexes, $newfilter);

            return redirect()->route('exercises', ['filters' => json_encode($filters), 'indexes' => json_encode($indexes)]);
        }

        /* Filter and show result */
        if( $request->has('indexes') && $request->has('filters') )
        {
            $filters = json_decode($request->input('filters'), true);
            $indexes = json_decode($request->input('indexes'), true);
            $exercises = Table::filter(new Exercise, $filters);
            $exercises = $exercises->get();
            $exercises = $this->resolveExercise($this->data_from, $this->data_to, $this->formats, $exercises);
            $names = $this->names;

            //maybe need to use Vue router!
            $url = route('exercises', ['filters' => json_encode($filters), 'indexes' => json_encode($indexes), 'newfilter' => 0]);
            
            //Set all filters
            $filters = $this->filters;
            
            //wrapping
            $url = json_encode($url);
            $names = json_encode($names);
            $filters = json_encode($filters);
            $indexes = json_encode($indexes);

            return view('exercises', compact('exercises', 'names', 'filters', 'indexes', 'url') );
        }

        /* Show all exercises */
        {
            $filters   = [];
            $indexes   = [];
            $bulk      = Bulk::where('user_id', Auth::user()->id)->first();
            if($bulk == null)
                $bulk_id = -1;
            else
                $bulk_id = $bulk->id;
            $exercises = Exercise::whereNotNull('id')->where('bulk_id', $bulk_id)->get();
            $exercises = $this->resolveExercise($this->data_from, $this->data_to, $this->formats, $exercises);
            $names     = $this->names;
            
            //maybe need to use Vue router!
            $url = route('exercises', ['filters' => json_encode($filters), 'indexes' => json_encode($indexes), 'newfilter' => 0]);
            
            //Set all filters
            $filters   = $this->filters;
            
            //wrapping
            $url = json_encode($url);
            $names = json_encode($names);
            $filters = json_encode($filters);
            $indexes = json_encode($indexes);

            return view('exercises', compact('exercises', 'names', 'filters', 'indexes', 'url') );
        }
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
        $exercise    = $this->resolveExercise($this->data_from, $this->data_to_2, $this->formats, $exercises);
        $names       = json_encode($this->names_2);

        Log::info("***** Exercise Controller ***** get_by_id end *****\n");

        //Do not use live_data_string (by AH)
        $live_data_string = null;

        return view('exercise', compact('id', 'exercise', 'names', 'live_data_string', 'addl_data_string',
                                        'pool_length', 'pool_length_unit', 'total_distance', 'total_duration') );
    }

    // ***** Functions *****
    public function resolveExercise($data_from, $data_to, $formats, $exercises)
    {
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
            'comment'    => function($x, $d)
            {
                if(mb_strlen($x) > 23)
                {
                    return mb_substr($x, 0, 20)."...";
                }
                else
                {
                    return $x;
                }
            }
        ];

        $exercises = Table::resolveFields($data_from, $data_to, $handlers, $formats, $exercises);
        return json_encode($exercises);
    }

    public function resolveAddl($data)
    {
        $data_from  =  [ 'duration'    , 'interval', 'stroke_count'      , 'stroke_type'                  ] ;
        $data_to    =  [ 'duration'    , 'interval', 'stroke_count'      , 'stroke_type' , 'swolf'        ] ;
        $formats    =  [ ["i","%I:%S"] ,  null     ,  null               ,  null         ,  ['float', 1]  ] ;

        $handlers = [
            'duration' => function($x){
                $x = $x / 1000;
                return new DateInterval('PT'.floor(round($x) / 60).'M'.(round($x) % 60).'S');
            },

            'swolf'    => function($x, $d){
                return $d['duration'] / 1000 + $d['stroke_count'];
            }
        ];

        $data = Table::resolveFields($data_from, $data_to, $handlers, $formats, $data);

        return json_encode($data);
    }
}

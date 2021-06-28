<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\ExerciseController;

use App\Exercise;
use App\Table;
use App\Bulk;

use Auth;

class HomeController extends Controller
{
    private $data_from      =  [ 'start_time'                     ,  'swolf'                    ] ;
    private $data_to        =  [ 'start_time'                     ,  'swolf'                    ] ;
    private $formats        =  [ ['dt','d-m-Y']                   , ['float',0]                 ] ;
    private $filters        =  [ ['start_time', 'asc_desc', null] , ['swolf', 'asc_desc', null] ] ;
    private $names          =  [ 'Дата'                           ,  'Swolf'                    ] ;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $bulk = Bulk::where('user_id', Auth::user()->id)->first();
        if($bulk == null)
        {
            $bulk_id = -1;
            $message = "Невозможно предоставить статистику!";
            $last_date = null;
            $mean_swolf = null;
            $all_duration = null;
            $best_mean_tempo_50 = null;
            $best_mean_tempo_100 = null;
            $best_mean_tempo_200 = null;
            $best_mean_tempo_1000 = null;
        }
        else
        {
            $bulk_id = $bulk->id;
            $message = "Статистика ваших тренировок";

            $mean_swolf = Exercise::where('bulk_id', $bulk_id)->whereNotNull('swolf')->avg('swolf');
            $last_date  = Exercise::where('bulk_id', $bulk_id)->max('start_time');
            $all_duration = Exercise::where('bulk_id', $bulk_id)->sum('duration');
            $best_mean_tempo_50   = Exercise::where('bulk_id', $bulk_id)->where('distance', 50)->min('mean_tempo');
            $best_mean_tempo_100  = Exercise::where('bulk_id', $bulk_id)->where('distance', 100)->min('mean_tempo');
            $best_mean_tempo_200  = Exercise::where('bulk_id', $bulk_id)->where('distance', 200)->min('mean_tempo');
            $best_mean_tempo_1000 = Exercise::where('bulk_id', $bulk_id)->where('distance', 1000)->min('mean_tempo');

            $mean_swolf = Table::fmt($mean_swolf, 'float', 1);
            $last_date = Table::fmt($last_date, 'dt', 'd-m-Y');
            $all_duration = Table::fmt($all_duration, 'i', '%h часов %I минут %S секунд');
            $best_mean_tempo_50 = Table::fmt($best_mean_tempo_50, 'i', '%I:%S');
            $best_mean_tempo_100 = Table::fmt($best_mean_tempo_100, 'i', '%I:%S');
            $best_mean_tempo_200 = Table::fmt($best_mean_tempo_200, 'i', '%I:%S');
            $best_mean_tempo_1000 = Table::fmt($best_mean_tempo_1000, 'i', '%I:%S');

            $message = json_encode($message);
            $last_date = json_encode($last_date);
            $all_duration = json_encode($all_duration);
            $best_mean_tempo_50 = json_encode($best_mean_tempo_50);
            $best_mean_tempo_100 = json_encode($best_mean_tempo_100);
            $best_mean_tempo_200 = json_encode($best_mean_tempo_200);
            $best_mean_tempo_1000 = json_encode($best_mean_tempo_1000);
        }

        // GRAPH CREATING
        if( $bulk_id == -1)
        {
            $label_x = null;
            $label_y = null;
            $input_data = null;
        }
        else
        {
            $handlers  = [];
            $exercises = Exercise::where('bulk_id', $bulk_id)->get();
            
            $exercises = Table::resolveFields($this->data_from, $this->data_to, $handlers, $this->formats, $exercises);
            $names     = $this->names;

            $label_x = $names[0];
            $label_y = $names[1];

            $input_data = [];
            for($i = 0; $i < count($exercises); $i++)
            {
                $x = $exercises[$i][ $this->data_to[0] ];
                $y = $exercises[$i][ $this->data_to[1] ] * 1;
                $input_data[] = ['x' => $x, 'y' => $y];
            }

            // Moving Average method (range = 4)
            $range = 10;
            $sum_y = 0;

            if(true && count($input_data) > $range + 2)
            {
                for($i = 0; $i < $range; $i++)
                {
                    $sum_y += $input_data[$i]['y'];
                }

                $g[] = ['x' => $input_data[0]['x'], 'y' => $sum_y / $range];

                for($i = $range; $i < count($input_data); $i++)
                {
                    $sum_y = $sum_y - $input_data[$i - $range]['y'] + $input_data[$i]['y'];
                    $g[] = ['x' => $input_data[$i - $range + 1]['x'], 'y' => $sum_y / $range];
                }

                $input_data = json_encode($g);
            }
            else
            {
                $input_data = json_encode($input_data);
            }
        }
        
        return view('home', compact('message', 'mean_swolf', 'best_mean_tempo_50', 'best_mean_tempo_100', 'input_data',
                                    'best_mean_tempo_200', 'best_mean_tempo_1000', 'last_date', 'all_duration', 'label_x', 'label_y'));
    }
}

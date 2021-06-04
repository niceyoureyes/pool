<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exercise;
use App\Table;
use App\Bulk;

use Auth;

class HomeController extends Controller
{
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
        }
        else
        {
            $bulk_id = $bulk->id;
            $message = "Статистика ваших тренировок";
        }
        $mean_swolf = Exercise::where('bulk_id', $bulk_id)->whereNotNull('swolf')->avg('swolf');
        $last_date  = Exercise::where('bulk_id', $bulk_id)->max('start_time');
        $all_duration = Exercise::where('bulk_id', $bulk_id)->sum('duration');
        $best_mean_tempo_50   = Exercise::where('bulk_id', $bulk_id)->where('distance', 50)->max('mean_tempo');
        $best_mean_tempo_100  = Exercise::where('bulk_id', $bulk_id)->where('distance', 100)->max('mean_tempo');
        $best_mean_tempo_200  = Exercise::where('bulk_id', $bulk_id)->where('distance', 200)->max('mean_tempo');
        $best_mean_tempo_1000 = Exercise::where('bulk_id', $bulk_id)->where('distance', 1000)->max('mean_tempo');

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
        
        return view('home', compact('message', 'mean_swolf', 'best_mean_tempo_50', 'best_mean_tempo_100',
                                    'best_mean_tempo_200', 'best_mean_tempo_1000', 'last_date', 'all_duration'));
    }
}

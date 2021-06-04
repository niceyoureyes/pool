<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\File;
use App\Bulk;
use App\Exercise;

use Auth;
use DateTime;

class FileController extends Controller
{
    // ***** Handlers *****
    public function load(Request $request)
    {
        Log::info("***** FileController ***** BEGIN loading *****");

        $path = $request->file('file')->store('uploads'.Auth::user()->id, 'public');
        $ext  = $request->file('file')->extension();
        $name = $request->name;

        Log::info('name       : '.$name);
        Log::info('client path: '.$request->file('file')->path());
        Log::info('path       : '.$path);
        Log::info('client ext : '.$request->file('file')->clientExtension());
        Log::info('ext        : '.$ext);

        //TODO maybe check not save double file
        $file = new File;
        $file->ext = $ext;
        $file->filename = $name;
        $file->stor_name = $path;
        $file->user_id = Auth::user()->id;
        $file->save();
        $file_id = $file->id;

        if($ext == 'txt')
        {
            $raw = $request->file('file')->get();
            $raws = explode("\n", $raw); // redundant
            
            if(count($raws) > 1)
            {
                Log::info('count exercises   : '.(count($raws) - 2));
                Log::info('length of typeline: '.strlen($raws[1]));

                $bulk = new Bulk;
                $bulk->file_id = $file_id;
                $bulk->type = $raws[0];
                $bulk->line = $raws[1];
                $bulk->user_id = Auth::user()->id;
                $bulk->save();
            }
        }

        Log::info("***** FileController ***** END loading *****\n");
        return;
    }

    public function get(Request $request)
    {
        $files = File::where('user_id', Auth::user()->id)->get();

        for($i = 0; $i < count($files); $i++)
        {
            $files[$i]["stor_name"] = substr($files[$i]["stor_name"], 0, 20);
        }

        return view('files', compact('files') );
    }

    public function clear(Request $request)
    {
        // TODO maybe delete only files
        $user_id = Auth::user()->id;

        $bulk = Bulk::where('user_id', $user_id)->first();
        if($bulk)
        {
            Exercise::where('bulk_id', $bulk->id)->delete();
        }

        Bulk::where('user_id', $user_id)->delete();
        File::where('user_id', $user_id)->delete();

        Storage::disk('public')->deleteDirectory('uploads'.Auth::user()->id);

        return redirect()->route('files');
    }

    public function resolve(Request $request)
    {
        Log::info("***** FileController ***** BEGIN resolving files *****");

        $user_id = Auth::user()->id;
        $bulk = Bulk::where('user_id', $user_id)->first();
        if($bulk != null) $bulk_id = $bulk->id; else return "No 'com.samsung.health.exercise' File!";
        Exercise::where('bulk_id', $bulk_id)->delete();

        $bulk = Bulk::find($bulk_id);

        if($bulk->type == "com.samsung.health.exercise")
        {
            $this->resolveExercise($bulk);
        }
        
        Log::info("***** FileController ***** END resolving files *****\n");
        return redirect()->route('exercises');
    }

    // ***** Functions *****
    public function resolveExercise(Bulk $bulk)
    {
        $data_from      = ['time_offset', 'start_time', 'end_time' , 'duration',
                           'max_speed'  , 'mean_speed', 'live_data', 'comment' ,
                           'distance'   , 'additional' ];
        
        $data_to        = [               'start_time', 'end_time'  , 'duration',
                           'max_tempo'  , 'mean_tempo', 'live_data' , 'comment' ,
                           'fists'      , 'scapula'   , 'flippers'  ,
                           'hand_w'     , 'foot_w'    , 'kolobashka',
                           'length_type', 'distance'  , 'addl_data' , 'swolf', 'stroke_count' ];

        $handlers = [
            'duration'    => function(&$x){
                return $x / 1000;
            },

            'mean_tempo'  => function(&$x, &$d){
                if( $d['mean_speed'] == 0)
                    return 0;
                else
                    return 100 / $d['mean_speed'];
            },

            'max_tempo'   => function(&$x, &$d){
                if( $d['max_speed'] == 0)
                    return 0;
                else
                    return 100 / $d['max_speed'];
            },

            'start_time'  => function(&$x, &$d){
                $offset_hours = $d['time_offset'] / 3600 / 1000;
                $dt = new DateTime($x);
                $dt->modify("-".round($offset_hours)." hour");
                return $dt->format('Y-m-d H:i:s');
            },

            'end_time'    => function(&$x, &$d){
                $offset_hours = $d['time_offset'] / 3600 / 1000;
                $dt = new DateTime($x);
                $dt->modify("-".round($offset_hours)." hour");
                return $dt->format('Y-m-d H:i:s');
            },

            'live_data'   => function(&$x){
                $data_file = File::where('filename', $x)->first();
                if ($data_file == null)
                {
                    return null;    
                }
                else{
                    $stor_name_file = $data_file->stor_name;
                    return Storage::disk('public')->get($stor_name_file);
                }
            },

            'addl_data'   => function(&$x, &$d){
                $x = $d['additional'];
                $data_file = File::where('filename', $x)->first();
                if ($data_file == null)
                {
                    return null;    
                }
                else{
                    $stor_name_file = $data_file->stor_name;
                    return Storage::disk('public')->get($stor_name_file);
                }
            },

            'fists'       => function(&$x, &$d){
                if(mb_stripos($d['comment'], 'кулаки') === false)
                    return 0;
                else
                    return 1;
            }, 
            
            'scapula'     => function(&$x, &$d){
                if(mb_stripos($d['comment'], 'лопатки') === false)
                    return 0;
                else
                    return 1;
            },
            
            'flippers'    => function(&$x, &$d){
                if(mb_stripos($d['comment'], 'ласты') === false)
                    return 0;
                else
                    return 1;
            },

            'hand_w'      => function(&$x, &$d){
                if(mb_stripos($d['comment'], 'руки') === false )
                    return 0;
                else
                    return 1;
            },
            
            'foot_w'      => function(&$x, &$d){
                if(mb_stripos($d['comment'], 'ноги') === false)
                    return 0;
                else
                    return 1;
            },
            
            'kolobashka'  => function(&$x, &$d){
                if(mb_stripos($d['comment'], 'колобашка') === false)
                    return 0;
                else
                    return 1;
            },

            'swolf'       => function(&$x, &$d, &$y){
                $r = Exercise::get_from_addldata($y['addl_data']);

                if($d['distance'] == 0)
                {
                    return null;
                }
                else{
                    return $r['swolf'] / $d['distance'] * 25;
                }
            },

            'stroke_count'=> function(&$x, &$d, &$y){
                $r = Exercise::get_from_addldata($y['addl_data']);
                return $r['stroke_count'];
            }
        ];

        //TODO class Handler function
        $db_data = $this->resolveFields($data_from, $data_to, $handlers, $bulk);
        Exercise::insert($db_data);
    }

    public function resolveFields($data_from_keys, $data_to_keys, $handlers, $bulk)
    {
        //TODO try to optimize code below
        $db_data            = [];
        $bulk_names         = explode(",", $bulk->line);
        $bulk_count         = count($bulk_names);
        $names_ids          = array_flip( array_intersect( $bulk_names, $data_from_keys ) );
        $data_from          = array_flip($data_from_keys);
        $data_to            = array_flip($data_to_keys);
        $data_to['id']      = Exercise::max('id') + 1;
        $data_to['bulk_id'] = $bulk->id;
        //TODO check that names not less

        $raw = Storage::disk('public')->get(File::find($bulk->file_id)->stor_name);
        $raws = explode("\n", $raw);

        for($i = 2; $i < count($raws); $i++)
        {
            $data = explode(",", $raws[$i]);

            //detect broken lines
            if(count($data) != $bulk_count)
                continue;

            // filling data_from
            for($x = current($data_from_keys); $x !== false; $x = next($data_from_keys))
            {
                $data_from[$x] = $data[$names_ids[$x]];
                if($data_from[$x] == "") $data_from[$x] = null;
            }

            // MAPPING
            for($x = current($data_to_keys); $x !== false; $x = next($data_to_keys))
            {
                $val = null;
                if(in_array($x, $data_from_keys))
                {
                    $val = $data_from[$x];
                }

                if(array_key_exists($x, $handlers))
                {
                    $data_to[$x] = $handlers[$x]($val, $data_from, $data_to);
                }
                else
                {
                    $data_to[$x] = $val;
                }
            }

            reset($data_to_keys);
            reset($data_from_keys);
            $data_to['id']++;
            $db_data[] = $data_to;
        }

        return $db_data;
    }
}

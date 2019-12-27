<?php

namespace App;

use Illuminate\Support\Facades\Log;

use DateTime;
use DateInterval;

class Handler
{
    public static function resolveFields($data_from_keys, $data_to_keys, $handlers, $formats, $data)
    {
        $out_data           = [];
        $data_from          = array_flip($data_from_keys);
        $data_to            = array_flip($data_to_keys);

        for($i = 0; $i < count($data); $i++)
        {
            // filling data_from
            for($x = current($data_from_keys); $x !== false; $x = next($data_from_keys))
            {
                $data_from[$x] = $data[$i][$x];
                if($data_from[$x] == "") $data_from[$x] = null;
            }

            // MAPPING
            $l = 0;
            for($x = current($data_to_keys); $x !== false; $x = next($data_to_keys))
            {
                $val = null;
                if(in_array($x, $data_from_keys))
                {
                    $val = $data_from[$x];
                }

                if(array_key_exists($x, $handlers))
                {
                    $data_to[$x] = Handler::fmt($handlers[$x]($val, $data_from), $formats[$l][0], $formats[$l][1]);
                }
                else
                {
                    $data_to[$x] = Handler::fmt($val, $formats[$l][0], $formats[$l][1]);
                }

                $l++;
            }

            reset($data_to_keys);
            reset($data_from_keys);
            $out_data[] = $data_to;
        }

        return $out_data;
    }

    public static function fmt($data, $type, $fm)
    {
        $res = '';

        if( $data === null || $type === null )
            return $data;

        // FROM STRING
        if( gettype($data) == 'string' )
        {
            if($type == 'dt')
            {
                $data = new DateTime($data);
            }
            else if($type == 'i')
            {
                $data = new DateInterval($data);
            }
            else if($type == 'float')
            {

            }
        }

        // PERFORM FMT
        if($type == 'float')
        {
            $res = number_format($data, $fm, '.', '');
        }
        else{
            $res = $data->format($fm);
        }

        return $res;
    }

    public static function filter(& $x, & $filt, & $indexes, $newfilter)
    {
        $newname = null;
        $newtype = null;
        $newval  = null;
        $index   = null;

        if($newfilter !== null)
        {
            $newname = $newfilter[0];
            $newtype = $newfilter[1];
            $newval  = $newfilter[2];
            $index   = $newfilter[3];
        }


        for($i = 0; $i < count($filt); $i++)
        {
            if($filt[$i] === null)
                continue;
                
            $name = $filt[$i][0];
            $type = $filt[$i][1];
            $val  = $filt[$i][2];

            if($name == $newname)
            {
                $newname = null;
                
                if($type == 'eq')
                {
                    $val = $newval;
                }
                else if($type == 'asc_desc')
                {
                    if($val == 'asc')
                    {
                        $val = 'desc';
                    }
                    else
                    {
                        unset($filt[$i]);
                        unset($indexes[$index]);
                        $filt = array_values($filt);
                        break;
                    }
                }
                else if($type == 'vals')
                {

                }
                else if($type == 'custom1')
                {

                }

                $filt[$i][0] = $name;
                $filt[$i][1] = $type;
                $filt[$i][2] = $val;
                $indexes[$index] = $val;
            }
        }


        if($newname !== null)
        {
            $d = null;

            if($newtype == 'eq')
            {
                $d = [null, null, null];
                $d[0] = $newname;
                $d[1] = $newtype;
                $d[2] = $newval;
                $indexes[$index] = $newval;
            }
            else if($newtype == 'asc_desc')
            {
                $d = [null, null, null];
                $d[0] = $newname;
                $d[1] = $newtype;
                $d[2] = 'asc';
                $indexes[$index] = 'asc';
            }
            else if($newtype == 'vals')
            {
                $r = 'duration';
                $y = $x->first()->replicate();
                $y = $y->orderBy($newname, 'asc')->first()->$r;
                Log::info($y);
            }
            else if($newtype == 'custom1')
            {

            }
            
            if($d !== null)
                $filt[] = $d;
        }


        for($i = 0; $i < count($filt); $i++)
        {
            if($filt[$i] === null)
                continue;
                
            $name = $filt[$i][0];
            $type = $filt[$i][1];
            $val  = $filt[$i][2];

            if($type == 'eq')
            {
                $x = $x->where($name, $val);
            }
            else if($type == 'asc_desc')
            {
                $x = $x->orderBy($name, $val);
            }
        }
    }
}

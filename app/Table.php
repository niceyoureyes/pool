<?php

namespace App;

use Illuminate\Support\Facades\Log;

use DateTime;
use DateInterval;

class Table
{
    /*
     *  Formats array of (named array) TO array of (named array)
     *  data_from_keys - fileds which should be format to
     *  data_to_keys   - new fields
     *  handlers, format
     *  data           - INPUT DATA
     *  
     *  Result:          OUTPUT DATA
     * 
     *  MAPPING:
     *  1) NULL
     *  2) field out = field input
     *  3) filed out = handler(x = null)
     *  4) field out = handler(x = field input)
     */
    public static function resolveFields($data_from_keys, $data_to_keys, $handlers, $formats, $data)
    {
        $out_data           = [];
        $data_from          = array_flip($data_from_keys);
        $data_to            = array_flip($data_to_keys);

        for($i = 0; $i < count($data); $i++)
        {
            // FILLING DATA FROM
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
                    $data_to[$x] = Table::fmt($handlers[$x]($val, $data_from), $formats[$l][0], $formats[$l][1]);
                }
                else
                {
                    $data_to[$x] = Table::fmt($val, $formats[$l][0], $formats[$l][1]);
                }

                $l++;
            }

            reset($data_to_keys);
            reset($data_from_keys);
            $out_data[] = $data_to;
        }

        return $out_data;
    }

    /*
     *  Formats data to data
     *  data - INPUT DATA (string or PHP type)
     *  type - dt(DateTime), i(Interval), f(float), null(do nothing)
     *  fm   - additional parameter for f(float)
     * 
     *  Result: OUTPUT DATA (string)
     */
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
        }

        if( gettype($data) == 'double' || gettype($data) == 'integer' )
        {
            if($type == 'i')
            {
                $data = new DateInterval('PT'.floor(round($data) / 60).'M'.(round($data) % 60).'S');
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

    /*  
        Create Filter

        Model = DB TABLE

        Order filtering:
        Filters[1] = [name, type, val]
        Filters[2] = [name, type, val]
        Filters[3] = [name, type, val]

        Column index filtering:
        Indexes['5'] = [name, type, val]
        Indexes['7'] = [name, type, val]

        Newfilter = new [name, type, val, index] for filter

        TYPE = asc_desc, eq, vals, custom
     */
    public static function create_filter($model, & $filt, & $indexes, $newfilter)
    {
        // INIT
        if($newfilter !== null)
        {
            $newname = $newfilter[0];
            $newtype = $newfilter[1];
            $newval  = $newfilter[2];
            $index   = $newfilter[3];
        }
        else
        {
            return;
        }

        // CHECK new filter in FILTERS
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
                    }
                }
                else if($type == 'vals')
                {
                    $m = $model::where($name, '>', $val)->orderBy($name, 'asc')->first();

                    if($m === null)
                    {
                        unset($filt[$i]);
                        unset($indexes[$index]);
                        $filt = array_values($filt);
                    }
                    else
                    {
                        $val = $m->$name;
                    }
                }
                else if($type == 'custom')
                {

                }
                
                $filt[$i][0] = $name;
                $filt[$i][1] = $type;
                $filt[$i][2] = $val;
                $indexes[$index] = $val;

                break;
            }
        }

        // CREATE new filter
        if($newname !== null)
        {
            if($newtype == 'eq')
            {

            }
            else if($newtype == 'asc_desc')
            {
                $newval = 'asc';
            }
            else if($newtype == 'vals')
            {
                $newval = $model::orderBy($newname, 'asc')->first()->$newname;
            }
            else if($newtype == 'custom')
            {

            }

            $d = [null, null, null];
            $d[0] = $newname;
            $d[1] = $newtype;
            $d[2] = $newval;
            $filt[] = $d;
            $indexes[$index] = $d;
        }
    }

    /*
     *  Filter DB TABLE according to filters
     * 
     *  Model = DB TABLE
     * 
     *  Order filtering:
     *  Filters[1] = [name, type, val]
     *  Filters[2] = [name, type, val]
     *  Filters[3] = [name, type, val]
     * 
     *  Result: DB Eloquent
     */
    public static function filter($model, $filt)
    {
        $x = $model::whereNotNull('id');

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
            else if($type == 'vals')
            {
                $x = $x->where($name, $val);
            }
            else if($type == 'custom')
            {

            }
        }

        return $x;
    }
}

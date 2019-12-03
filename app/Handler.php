<?php

namespace App;

use Illuminate\Support\Facades\Log;

use DateTime;
use DateInterval;

class Handler
{
    public function resolveFields($data_from_keys, $data_to_keys, $handlers, $formats, $data)
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
                    $data_to[$x] = $this->fmt($handlers[$x]($val, $data_from), $formats[$l][0], $formats[$l][1]);
                }
                else
                {
                    $data_to[$x] = $this->fmt($val, $formats[$l][0], $formats[$l][1]);
                }

                $l++;
            }

            reset($data_to_keys);
            reset($data_from_keys);
            $out_data[] = $data_to;
        }

        return $out_data;
    }

    public function fmt($data, $type, $fm)
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
}

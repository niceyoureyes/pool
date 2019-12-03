<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        
    ];

    protected $hidden = [
        'bulk_id', 'live_data', 'addl_data',
        'fists', 'scapula', 'flippers', 'hand_w', 'foot_w', 'kolobashka',
    ];

    public $timestamps = false;

    public static function get_from_addldata($addl_data_blob)
    {
        $res = ['stroke_count' => 0, 'stroke_type' => null, 'swolf' => 0];

        $addl_data_string = null;
        if($addl_data_blob != null)
        {
            $addl_data_string = gzdecode($addl_data_blob);
            $addl_data_names  = ["duration", "interval", "stroke_count", "stroke_type"];
            $addl_data_array  =  json_decode($addl_data_string, true);
            $data             = $addl_data_array['lengths'];

            for($i = 0; $i < count($data); $i++)
            {
                $data[$i] = array_intersect_key($data[$i], array_flip($addl_data_names));
                
                $res['swolf']        += $data[$i]['duration'] / 1000;
                $res['stroke_count'] += $data[$i]['stroke_count'];
            }

            $res['swolf'] += $res['stroke_count'];
        }

        return $res;
    }
}

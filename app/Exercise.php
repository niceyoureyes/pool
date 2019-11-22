<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        
    ];

    protected $hidden = [
        'id', 'bulk_id', 'live_data', 'addl_data',
        'fists', 'scapula', 'flippers', 'hand_w', 'foot_w', 'kolobashka', 'length_type'
    ];

    public $timestamps = false;
}

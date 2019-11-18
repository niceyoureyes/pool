<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'id', 'bulk_id', 'start_time', 'duration'
    ];

    protected $hidden = [
        'live_data'
    ];

    public $timestamps = false;
}

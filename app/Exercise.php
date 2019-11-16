<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'id', 'bulk_id', 'start_time', 'duration'
    ];

    public $timestamps = false;
}

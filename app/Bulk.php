<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bulk extends Model
{
    protected $fillable = [
        'id', 'file_id', 'type', 'line'
    ];

    public $timestamps = false;
}

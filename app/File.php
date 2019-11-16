<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'id', 'filename', 'stor_name', 'ext'
    ];

    public $timestamps = false;
}

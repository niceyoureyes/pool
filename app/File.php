<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'id', 'filename', 'stor_name', 'ext'
    ];

    protected $hidden = [
        'id', 'user_id'
    ];

    public $timestamps = false;
}

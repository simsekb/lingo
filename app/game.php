<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class game extends Model
{
    protected $fillable = [
        'uuid', 'word'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}

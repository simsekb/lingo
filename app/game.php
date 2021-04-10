<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'uuid', 'word'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}

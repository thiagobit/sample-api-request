<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'path',
        'method',
        'params',
        'status',
        'reason',
        'body',
    ];
}

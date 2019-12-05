<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $casts = [
        'is_done' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $table = 'targets';

    protected $fillable = [
        'id',

        'excluded_target',
        'target_domain',
        'referring_domain',
        'rank',
        'backlinks',

        'created_at',
        'updated_at'
    ];
}

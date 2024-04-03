<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    use HasFactory;

    protected $table = 'from_data';

    protected $fillable = [
        'id',

        'target',
        'exclude_targets',
        'status',

        'created_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\FormData;

class Error extends Model
{
    use HasFactory;

    protected $table = 'errors';

    protected $fillable = [
        'id',

        'data_id',
        'version',
        'status_code',

        'status_message',
        'time',
        'cost',
        'tasks_count',
        'tasks_error',
        'tasks',

        'created_at',
        'updated_at'
    ];

    /* ************************ Relations ************************* */
    public function FormData()
    {
        return $this->belongsTo(FormData::class, 'data_id');
    }
}

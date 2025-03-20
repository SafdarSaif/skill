<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'student_id',
        'name',
        'email',
        'phone',
        'query',
        'answer',
        'attachment',
    ];
}


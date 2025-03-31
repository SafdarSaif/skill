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
        'email',
        'phone',
        'query',
        'answer',
        'status',
        'attachment',
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id'); // Assuming 'student_id' is the foreign key in 'student_queries' table
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;

    protected $table = 'student_progress';

    protected $fillable = [
        'student_id',
        'video_id',
        'subject_id',
        'course_id',
        'subject_name',
        'total_duration',
        'watch_time',
        'progress',
        'progress_status'
    ];


    // Define Relationship with Student Model
    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    // Define Relationship with Course Model
    public function course()
    {
        return $this->belongsTo(StudentCourse::class, 'course_id');
    }

    // Define Relationship with Subject Model
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // public function video()
    // {
    //     return $this->belongsTo(StudentProgress::class, 'video_id');
    // }
    public function video()
    {
        return $this->belongsTo(SubjectVideo::class, 'video_id');
    }


    
}

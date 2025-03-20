<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StudentCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'student_payment_id',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class, 'course_id');
    }


    public function studentCourse(){
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function progress()
    {
        return $this->hasMany(StudentProgress::class, 'student_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Students;
use App\Models\Course;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'course_id', 
        'amount',
        'transaction_id', 
        'payment_status', 
        'payment_confirmation_date'
    ];

    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

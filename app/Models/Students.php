<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Students extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'dob',
        'mobile',
        'fathers_name',
        'mothers_name',
        'address', 
        'state',
        'district',
        'city',
        'pincode',
        'country',
        'heighest_qualification',
        'status'
    ];

    public function studentCourses(){
        return $this->hasMany(StudentCourse::class, 'student_id')->with('course');
    }
}
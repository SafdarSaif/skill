<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'status',
        'image',
        'signature',
        'added_by',
        'device_token',
        'mobile_id',
        'session_id',
    ];


    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class, 'student_id')->with('course');
    }


    /**
     * Define relationship: A student has many progress records.
     */
    public function progress()
    {
        return $this->hasMany(StudentProgress::class, 'student_id');
    }




    /**
     * Define relationship: A student has many news.
     */
    public function readNews()
    {
        return $this->belongsToMany(NewsUpdate::class, 'news_reads')
            ->withPivot('read_at');
    }
}

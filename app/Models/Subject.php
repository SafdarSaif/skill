<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'description',
        'status',
    ];

    /**
     * Define the relationship with the Course model.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

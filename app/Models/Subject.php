<?php

namespace App\Models;

use App\Http\Controllers\EbookController;
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
        'image',
    ];

    /**
     * Define the relationship with the Course model.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function videos()
    {
        return $this->hasMany(SubjectVideo::class);
    }

    public function notes()
    {
        return $this->hasMany(SubjectNote::class);
    }

    public function eBooks()
    {
        return $this->hasMany(Ebook::class);
    }
}

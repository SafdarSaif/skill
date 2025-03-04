<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'duration', 
        'category_id', 
        'status'
    ];

    /**
     * Get the category associated with the course.
     */
    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id');
    // }
}

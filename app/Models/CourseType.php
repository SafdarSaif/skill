<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseType extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class,'type_id','id')->with('users');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectNote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subject_notes'; 

    protected $fillable = [
        'type_id',
        'category_id',
        'course_id',
        'subject_id',
        'name',
        'description',
        'user_id',
        'upload_type',
        'file_path',
        'url',
    ];

    /**
     * Relationship: A subject note belongs to a subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Relationship: A subject note belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}

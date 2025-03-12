<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectNote extends Model
{
    use HasFactory;

    protected $table = 'subject_notes'; 

    protected $fillable = [
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

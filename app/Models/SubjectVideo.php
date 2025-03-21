<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentProgress;

class SubjectVideo extends Model {
    use HasFactory;

    protected $fillable = [
        'subject_id', 'name', 'description', 'duration',
        'user_id', 'position', 'upload_type', 'video_url'
    ];

    // Relationship with Subject
    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    // Relationship with User (Uploader)
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function progress()
{
    return $this->hasOne(StudentProgress::class, 'video_id', 'id');
}

}


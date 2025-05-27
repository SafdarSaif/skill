<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentProgress;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectVideo extends Model {
    use HasFactory;
    use SoftDeletes;
    

    protected $fillable = [
        'type_id', 'category_id', 'course_id',
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


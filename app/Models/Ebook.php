<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ebook extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'subject_id', 
        'name', 
        'description', 
        'user_id', 
        'upload_type', 
        'file_location', 
        'external_link', 
        'status'
    ];

    /**
     * Relationship with Subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relationship with User (Uploader).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
    
}

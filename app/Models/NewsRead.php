<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsRead extends Model
{
    public $timestamps = false;

    protected $fillable = ['student_id', 'news_update_id', 'read_at'];

    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function newsUpdate()
    {
        return $this->belongsTo(NewsUpdate::class);
    }
}

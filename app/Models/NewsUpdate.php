<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class NewsUpdate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'content', 'image', 'status'];

    public function readers()
    {
        return $this->belongsToMany(Students::class, 'news_reads')
            ->withPivot('read_at');
    }
}

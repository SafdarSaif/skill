<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'student_id',
        'email',
        'phone',
        'query',
        'answer',
        'status',
        'attachment',
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id'); // Assuming 'student_id' is the foreign key in 'student_queries' table
    }

    public function video()
    {
        return $this->belongsTo(SubjectVideo::class, 'video_id');
    }

    public static function getGroupedStudentQueries()
    {
        return self::select(
                'student_id',
                DB::raw('MAX(id) as latest_id'),
                DB::raw('COUNT(*) as total_queries')
            )
            ->groupBy('student_id')
            ->with('student')
            ->orderByDesc('latest_id')
            ->get();
    }
}

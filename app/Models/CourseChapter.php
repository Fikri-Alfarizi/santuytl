<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'title', 'content', 'video_url', 'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

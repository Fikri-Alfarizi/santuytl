<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'thumbnail', 'xp_reward', 'coin_reward', 'is_active'
    ];

    public function chapters()
    {
        return $this->hasMany(CourseChapter::class)->orderBy('order');
    }

    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\UserCourse;
use App\Models\UserStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_active', true)->get();
        return view('courses.index', compact('courses'));
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)->with('chapters')->firstOrFail();
        $userCourse = null;
        
        if (Auth::check()) {
            $userCourse = UserCourse::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();
        }

        return view('courses.show', compact('course', 'userCourse'));
    }

    public function start($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        
        $userCourse = UserCourse::firstOrCreate([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
        ], [
            'current_chapter_id' => $course->chapters->first()->id ?? null,
        ]);

        return redirect()->route('courses.chapter', [$course->slug, $course->chapters->first()->id]);
    }

    public function showChapter($slug, $chapterId)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $chapter = CourseChapter::where('course_id', $course->id)->findOrFail($chapterId);
        
        $userCourse = UserCourse::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Update current chapter if not completed
        if (!$userCourse->is_completed) {
            $userCourse->update(['current_chapter_id' => $chapter->id]);
        }

        $nextChapter = CourseChapter::where('course_id', $course->id)
            ->where('order', '>', $chapter->order)
            ->orderBy('order')
            ->first();

        return view('courses.chapter', compact('course', 'chapter', 'userCourse', 'nextChapter'));
    }

    public function completeChapter($slug, $chapterId)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $chapter = CourseChapter::where('course_id', $course->id)->findOrFail($chapterId);
        
        $userCourse = UserCourse::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        $nextChapter = CourseChapter::where('course_id', $course->id)
            ->where('order', '>', $chapter->order)
            ->orderBy('order')
            ->first();

        if (!$nextChapter) {
            // Course Completed
            if (!$userCourse->is_completed) {
                $userCourse->update([
                    'is_completed' => true,
                    'completed_at' => now(),
                ]);

                // Give Rewards
                $userStats = UserStat::firstOrCreate(['user_id' => Auth::id()]);
                if ($course->xp_reward) $userStats->increment('xp', $course->xp_reward);
                if ($course->coin_reward) $userStats->increment('coins', $course->coin_reward);

                return redirect()->route('courses.show', $course->slug)->with('success', 'Course Completed! Rewards claimed.');
            }
            return redirect()->route('courses.show', $course->slug);
        }

        return redirect()->route('courses.chapter', [$course->slug, $nextChapter->id]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\SubscribeTransaction;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\CourseStudent;

class DashboardController extends Controller
{
    //Digunakan oleh 2 role
    public function index()
    {
        // Ambil data user yang sedang login
        $user = auth()->user();
        $courseQuery = Course::query();

        // Cek apakah user adalah teacher
        if ($user->hasRole('teacher')) {
            // Jika teacher, ambil course yang dimiliki oleh teacher tersebut
            $courseQuery->whereHas('teacher', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            $students = CourseStudent::whereIn('course_id', $courseQuery->select('id'))->distinct('user_id')->count('user_id');
        } else {
            $students = CourseStudent::distinct('user_id')->count();
        }

        $courses = $courseQuery->count();
        $categories = Category::count();
        $transactions = SubscribeTransaction::count();
        $teachers = Teacher::count();

        return view('dashboard', compact('courses', 'students', 'categories', 'transactions', 'teachers'));

    }
}

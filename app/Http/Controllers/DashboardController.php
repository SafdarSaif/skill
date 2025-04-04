<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use App\Models\Course;
use App\Models\User;
use App\Models\StudentCourse;
use App\Models\StudentPayment; 
use Carbon\Carbon;


class DashboardController extends Controller
{
    // public function index()
    // {
    //     // Fetch total student count
    //     $studentCount = Students::count();

    //     // Fetch total course count
    //     $courseCount = Course::count();

    //     // Get current month's student count
    //     $currentMonthStudentCount = Students::whereMonth('created_at', Carbon::now()->month)
    //         ->whereYear('created_at', Carbon::now()->year)
    //         ->count();

    //     // Get last month's student count
    //     $lastMonthStudentCount = Students::whereMonth('created_at', Carbon::now()->subMonth()->month)
    //         ->whereYear('created_at', Carbon::now()->subMonth()->year)
    //         ->count();

    //     // Debugging output
    //     \Log::info([
    //         'current_month_count' => $currentMonthStudentCount,
    //         'last_month_count' => $lastMonthStudentCount
    //     ]);

    //     // Calculate student growth percentage
    //     if ($lastMonthStudentCount == 0) {
    //         $studentGrowth = $currentMonthStudentCount > 0 ? 100 : 0; // If no students last month, assume 100% if any exist
    //     } else {
    //         $studentGrowth = (($currentMonthStudentCount - $lastMonthStudentCount) / $lastMonthStudentCount) * 100;
    //     }

    //     return view('content.home', compact('studentCount', 'courseCount', 'studentGrowth'));
    // }

    public function index()
    {
        // Fetch total student count
        $studentCount = Students::count();
    
        // Fetch total course count
        $courseCount = Course::count();
    
        // Fetch total users count
        $userCount = User::count(); // Assuming 'User' is your model for users
    
        // Fetch total revenue where payment_status is 'completed'
        $totalRevenue = StudentPayment::where('payment_status', 'completed')->sum('amount');
    
        // Get current month's student count
        $currentMonthStudentCount = Students::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
    
        // Get last month's student count
        $lastMonthStudentCount = Students::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
    
        // Calculate student growth percentage
        if ($lastMonthStudentCount == 0) {
            $studentGrowth = $currentMonthStudentCount > 0 ? 100 : 0;
        } else {
            $studentGrowth = (($currentMonthStudentCount - $lastMonthStudentCount) / $lastMonthStudentCount) * 100;
        }
    
        return view('content.home', compact('studentCount', 'courseCount', 'userCount', 'totalRevenue', 'studentGrowth'));
    }
    


    // public function index() {
    //     // Total Students
    //     $studentCount = Students::count();

    //     // Growth Calculation (Assuming a `created_at` column exists)
    //     $lastMonthStudents = Students::whereMonth('created_at', now()->subMonth()->month)->count();
    //     $studentGrowth = $lastMonthStudents > 0 ? (($studentCount - $lastMonthStudents) / $lastMonthStudents) * 100 : 0;

    //     // Active Courses
    //     $courseCount = Course::where('status', 1)->count();

    //     // Completion Rate (Example Calculation)
    //     $completionRate = StudentCourse::where('status', 'completed')->count() / StudentCourse::count() * 100;

    //     // Average Progress (Example)
    //     $avgProgress = StudentCourse::avg('progress'); 

    //     // Recent Enrollments (Last 5)
    //     $recentEnrollments = StudentCourse::with('student', 'course')
    //         ->latest()
    //         ->take(5)
    //         ->get();

    //     return view('dashboard', compact('studentCount', 'studentGrowth', 'courseCount', 'completionRate', 'avgProgress', 'recentEnrollments'));
    // }
}

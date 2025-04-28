<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use App\Models\Course;
use App\Models\User;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{



    // public function index()
    // {
    //     $studentCount = Students::count();
    //     $courseCount = Course::count();
    //     $userCount = User::count();

    //     $totalRevenue = StudentPayment::where('payment_status', 'completed')->sum('amount');

    //     $currentMonthStudentCount = Students::whereMonth('created_at', Carbon::now()->month)
    //         ->whereYear('created_at', Carbon::now()->year)
    //         ->count();

    //     $lastMonthStudentCount = Students::whereMonth('created_at', Carbon::now()->subMonth()->month)
    //         ->whereYear('created_at', Carbon::now()->subMonth()->year)
    //         ->count();

    //     if ($lastMonthStudentCount == 0) {
    //         $studentGrowth = $currentMonthStudentCount > 0 ? 100 : 0;
    //     } else {
    //         $studentGrowth = (($currentMonthStudentCount - $lastMonthStudentCount) / $lastMonthStudentCount) * 100;
    //     }

    //     // 👇 Recent Enrollments (adjust model and relationships as needed)
    //     $recentEnrollments = Studentcourse::with(['student', 'course'])
    //         ->latest()
    //         ->take(5)
    //         ->get();

    //     // 👇 Courses with completion rate (if required elsewhere)
    //     $courses = Course::with('students')->get()->map(function ($course) {
    //         $course->completion_rate = rand(50, 100); 
    //         return $course;
    //     });

    //     return view('content.home', compact(
    //         'studentCount',
    //         'courseCount',
    //         'userCount',
    //         'totalRevenue',
    //         'studentGrowth',
    //         'recentEnrollments',
    //         'courses'
    //     ));
    // }
    public function index()
    {
        $studentCount = Students::where('status', 1)->count();
        $courseCount = Course::where('status', 1)->count();
        $userCount   = User::where('status', 1)->count();
        

        $totalRevenue = StudentPayment::where('payment_status', 'completed')->sum('amount');

        $currentMonthStudentCount = Students::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $lastMonthStudentCount = Students::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        if ($lastMonthStudentCount == 0) {
            $studentGrowth = $currentMonthStudentCount > 0 ? 100 : 0;
        } else {
            $studentGrowth = (($currentMonthStudentCount - $lastMonthStudentCount) / $lastMonthStudentCount) * 100;
        }

        $recentEnrollments = Studentcourse::with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        $totalEnrollments = Studentcourse::count();

        // $courses = Course::with('students')->get()->map(function ($course) {
        //     $course->completion_rate = rand(50, 100); 
        //     return $course;
        // });
        // $courses = Course::withCount('students')
        //     ->latest()
        //     ->take(10)
        //     ->get();
        // dd($courses);
        $courses = Course::withCount('students')
        ->latest()
        ->get() 
        ->map(function ($course) use ($totalEnrollments) {
            $course->enrollment_percent = $totalEnrollments > 0
                ? round(($course->students_count / $totalEnrollments) * 100, 1)
                : 0;
            return $course;
        })
        ->sortByDesc('students_count') 
        ->take(10);

        return view('content.home', compact(
            'studentCount',
            'courseCount',
            'userCount',
            'totalRevenue',
            'studentGrowth',
            'recentEnrollments',
            'totalEnrollments',
            'courses'
        ));
    }

    public function getEnrollmentData(Request $request)
    {
        $type = $request->query('type', 'weekly');
        $year = $request->query('year', Carbon::now()->year);

        if ($type === 'weekly') {
            $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfWeek();
            $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfWeek();

            $startOfWeek = Carbon::now()->startOfWeek();
            if ($startOfWeek->year !== (int)$year) {
                $startOfWeek = $startOfYear;
            }
            $endOfWeek = $startOfWeek->copy()->endOfWeek();

            $weeklyData = Students::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = [];
            $values = [];
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
                $labels[] = $startOfWeek->copy()->addDays($i)->format('D'); // Mon, Tue...
                $dayData = $weeklyData->firstWhere('date', $day);
                $values[] = $dayData ? $dayData->total : 0;
            }
        } else {
            // Fetch monthly enrollment data for the selected year
            $monthlyData = Students::whereYear('created_at', $year)
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Labels and values for each month (Jan - Dec)
            $labels = [];
            $values = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->format('M'); // Jan, Feb...
                $monthData = $monthlyData->firstWhere('month', $i);
                $values[] = $monthData ? $monthData->total : 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }


    public function getPaymentData(Request $request)
    {
        $type = $request->query('type', 'weekly');
        $year = $request->query('year', Carbon::now()->year);

        if ($type === 'weekly') {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $weeklyData = StudentPayment::where('payment_status', 'completed')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = [];
            $values = [];
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
                $labels[] = $startOfWeek->copy()->addDays($i)->format('D'); // Mon, Tue...
                $dayData = $weeklyData->firstWhere('date', $day);
                $values[] = $dayData ? $dayData->total : 0;
            }
        } else {
            $monthlyData = StudentPayment::where('payment_status', 'completed')
                ->whereYear('created_at', $year)
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $labels = [];
            $values = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->format('M'); // Jan, Feb...
                $monthData = $monthlyData->firstWhere('month', $i);
                $values[] = $monthData ? $monthData->total : 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }



}
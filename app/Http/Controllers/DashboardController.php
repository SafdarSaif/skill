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



    public function index()
    {
        $studentCount = Students::count();
        $courseCount = Course::count();
        $userCount = User::count();

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

        // 👇 Recent Enrollments (adjust model and relationships as needed)
        $recentEnrollments = Studentcourse::with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        // 👇 Courses with completion rate (if required elsewhere)
        $courses = Course::with('students')->get()->map(function ($course) {
            $course->completion_rate = rand(50, 100); // Replace with actual logic if needed
            return $course;
        });

        return view('content.home', compact(
            'studentCount',
            'courseCount',
            'userCount',
            'totalRevenue',
            'studentGrowth',
            'recentEnrollments',
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



    // public function getEnrollmentData(Request $request)
    // {
    //     $type = $request->query('type', 'weekly');

    //     if ($type === 'weekly') {
    //         // Group enrollments by day for the current week
    //         $startOfWeek = Carbon::now()->startOfWeek();
    //         $weeklyData = Students::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
    //             ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
    //             ->groupBy('date')
    //             ->orderBy('date')
    //             ->get();

    //             // dd($weeklyData);

    //         // Create labels and values for each day (Mon to Sun)
    //         $labels = [];
    //         $values = [];
    //         for ($i = 0; $i < 7; $i++) {
    //             $day = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
    //             $labels[] = $startOfWeek->copy()->addDays($i)->format('D'); // Mon, Tue...
    //             $dayData = $weeklyData->firstWhere('date', $day);
    //             $values[] = $dayData ? $dayData->total : 0;
    //         }
    //     } else {
    //         // Monthly data for the current year
    //         $monthlyData = Students::whereYear('created_at', Carbon::now()->year)
    //             ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
    //             ->groupBy('month')
    //             ->orderBy('month')
    //             ->get();

    //             // dd($monthlyData);

    //         // Labels for all months
    //         $labels = [];
    //         $values = [];
    //         for ($i = 1; $i <= 12; $i++) {
    //             $labels[] = Carbon::create()->month($i)->format('M'); // Jan, Feb...
    //             $monthData = $monthlyData->firstWhere('month', $i);
    //             $values[] = $monthData ? $monthData->total : 0;
    //         }
    //     }

    //     return response()->json([
    //         'labels' => $labels,
    //         'values' => $values
    //     ]);
    // }

    // public function getEnrollmentData(Request $request)
    // {
    // //     $type = $request->query('type', 'weekly');
    // //     $year = $request->query('year', Carbon::now()->year); 

    // //     if ($type === 'weekly') {
    // //         $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfWeek();
    // //         $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfWeek();

    // //         $startOfWeek = Carbon::now()->startOfWeek();
    // //         if ($startOfWeek->year !== (int)$year) {
    // //             // Adjust the week range to match the requested year
    // //             $startOfWeek = $startOfYear;
    // //         }
    // //         $endOfWeek = $startOfWeek->copy()->endOfWeek();

    // //         // Fetch weekly enrollment data
    // //         $weeklyData = Students::whereBetween('created_at', [$startOfWeek, $endOfWeek])
    // //             ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
    // //             ->groupBy('date')
    // //             ->orderBy('date')
    // //             ->get();

    // //         // Create labels and values for each day (Monday to Sunday)
    // //         $labels = [];
    // //         $values = [];
    // //         for ($i = 0; $i < 7; $i++) {
    // //             $day = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
    // //             $labels[] = $startOfWeek->copy()->addDays($i)->format('D'); // Mon, Tue...
    // //             $dayData = $weeklyData->firstWhere('date', $day);
    // //             $values[] = $dayData ? $dayData->total : 0;
    // //         }
    // //     } else {
    // //         // Fetch monthly enrollment data for the selected year
    // //         $monthlyData = Students::whereYear('created_at', $year)
    // //             ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
    // //             ->groupBy('month')
    // //             ->orderBy('month')
    // //             ->get();

    // //         // Labels and values for each month (Jan - Dec)
    // //         $labels = [];
    // //         $values = [];
    // //         for ($i = 1; $i <= 12; $i++) {
    // //             $labels[] = Carbon::create()->month($i)->format('M'); // Jan, Feb...
    // //             $monthData = $monthlyData->firstWhere('month', $i);
    // //             $values[] = $monthData ? $monthData->total : 0;
    // //         }
    // //     }

    // //     return response()->json([
    // //         'labels' => $labels,
    // //         'values' => $values
    // //     ]);
    // }

    // public function getEnrollmentData(Request $request)
    // {
    //     $type = $request->query('type', 'weekly');
    //     $year = $request->query('year', Carbon::now()->year); // Default to current year

    //     if ($type === 'weekly') {
    //         // Fetch weekly enrollment data grouped into 4 weeks per month
    //         $weeklyData = Students::whereYear('created_at', $year)
    //             ->select(
    //                 DB::raw('MONTH(created_at) as month'),
    //                 DB::raw('CEIL(DAY(created_at) / 7) as week_of_month'),
    //                 DB::raw('COUNT(*) as total')
    //             )
    //             ->groupBy('month', 'week_of_month')
    //             ->orderBy('month')
    //             ->orderBy('week_of_month')
    //             ->get();
    //         // dd($weeklyData);

    //         // Labels and values for 4 weeks per month
    //         $labels = [];
    //         $values = [];

    //         for ($month = 1; $month <= 12; $month++) {
    //             for ($week = 1; $week <= 4; $week++) {
    //                 $labels[] = Carbon::create()->month($month)->format('M') . " - Week $week";
    //                 $weekData = $weeklyData->firstWhere('month', $month)->firstWhere('week_of_month', $week);
    //                 $values[] = $weekData ? $weekData->total : 0;
    //             }
    //         }
    //     } else {
    //         // Fetch monthly enrollment data for the selected year
    //         $monthlyData = Students::whereYear('created_at', $year)
    //             ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
    //             ->groupBy('month')
    //             ->orderBy('month')
    //             ->get();

    //         // Labels and values for each month (Jan - Dec)
    //         $labels = [];
    //         $values = [];
    //         for ($i = 1; $i <= 12; $i++) {
    //             $labels[] = Carbon::create()->month($i)->format('M'); // Jan, Feb...
    //             $monthData = $monthlyData->firstWhere('month', $i);
    //             $values[] = $monthData ? $monthData->total : 0;
    //         }
    //     }

    //     return response()->json([
    //         'labels' => $labels,
    //         'values' => $values
    //     ]);
    // }



}

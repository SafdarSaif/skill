<?php

namespace App\Http\Controllers;

use App\Models\StudentPayment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use App\Models\Course;
use App\Models\Students;

class StudentPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = Students::pluck('name', 'id');
        $courses = Course::pluck('name', 'id');

        if ($request->ajax()) {
            $data = StudentPayment::with(['student', 'course'])
                ->orderBy('id', 'desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('student_id', function ($data) {
                    return $data->student ? $data->student->name : 'N/A';
                })
                ->editColumn('course_id', function ($data) {
                    return $data->course ? $data->course->name : 'N/A';
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }

        return view('studentpayment.index', compact('students', 'courses'));
    }


    // API by KP
    // public static function StudentPayment($mobile)
    // {
    //     try {
    //         $student = Students::where('mobile', $mobile)->first();
    //         if ($student) {
    //             $data = StudentPayment::where('student_id', $student->id)->with('course')->get();
    //             return response()->json(['status' => 'success', 'data' => $data]);
    //         } else {
    //             return response()->json(['status' => 'success', 'message' => 'Student not found']);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error','message' => 'An error occurred while fetching student payment details'], 500);
    //     }
    // }

    public static function StudentPayment($mobile)
    {
        try {
            $student = Students::where('mobile', $mobile)->first();
            if ($student) {
                $data = StudentPayment::where('student_id', $student->id)
                    ->with('course')
                    ->orderBy('id', 'desc')
                    ->get();
                return response()->json(['status' => 'success', 'data' => $data]);
            } else {
                return response()->json(['status' => 'success', 'message' => 'Student not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while fetching student payment details'], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $student = Students::pluck('name', 'id');
        $course = Course::pluck('name', 'id');

        return view('studentpayment.create', compact('student', 'course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'student_id' => 'required|integer|exists:students,id',
    //         'course_id' => 'required|integer|exists:courses,id',
    //         // 'transaction_id' => 'required|string|unique:payments,transaction_id',
    //         'transaction_id' => 'required|string|unique:student_payments,transaction_id',
    //         'payment_status' => 'required|in:pending,completed,failed',
    //         'payment_confirmation_date' => 'nullable|date',
    //     ]);

    //     try {
    //         $payment = new StudentPayment();
    //         $payment->student_id = $request->student_id;
    //         $payment->course_id = $request->course_id;
    //         $payment->transaction_id = $request->transaction_id;
    //         $payment->payment_status = $request->payment_status;
    //         $payment->payment_confirmation_date = $request->payment_confirmation_date;
    //         $payment->save();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Payment recorded successfully!',
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Failed to record payment: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }


    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'amount' => 'required|decimal',
            'course_id' => 'required|integer|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string|unique:student_payments,transaction_id',
            'payment_status' => 'required|in:pending,completed,failed',
            'payment_confirmation_date' => 'nullable|date',
        ]);

        try {
            $payment = new StudentPayment();
            $payment->student_id = $request->student_id;
            $payment->course_id = $request->course_id;
            $payment->amount = $request->amount;
            $payment->transaction_id = $request->transaction_id;
            $payment->payment_status = $request->payment_status;
            $payment->payment_confirmation_date = $request->payment_confirmation_date;
            $payment->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment recorded successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to record payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentPayment $studentPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentPayment $studentPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentPayment $studentPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentPayment $studentPayment)
    {
        //
    }

    public function getPyamentByStudentIdCourseId($studentId, $courseId)
    {
        $transactionData = StudentPayment::where('course_id', $courseId)->where('student_id', $studentId)->get();
        if ($transactionData->isNotEmpty()) {
            return response()->json(['status' => 'success', 'payments' => $transactionData]);
        }
        return response()->json(['status' => 'error']);
    }
}

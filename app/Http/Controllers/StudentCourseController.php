<?php

namespace App\Http\Controllers;

use App\Models\StudentCourse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use App\Models\Course;
use App\Models\Students;
use App\Models\StudentPayment;

class StudentCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentCourse::with(['student', 'course'])
                ->orderBy('id', 'desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y h:i A') : 'N/A';
                })
                ->addColumn('student_name', function ($data) {
                    return $data->student ? $data->student->name : 'N/A';
                })
                ->addColumn('course_name', function ($data) {
                    return $data->course ? $data->course->name : 'N/A';
                })
                ->make(true);
        }

        return view('studentcourse.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $student = Students::pluck('name', 'id');
        $course = Course::pluck('name', 'id');
        // $studentpayment = StudentPayment::pluck('transaction_id', 'id');


        return view('studentcourse.create', compact('student', 'course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'payment_id' => 'required|exists:student_payments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $payment = StudentPayment::find($request->payment_id);
        if (!$payment || $payment->payment_status !== 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment is not completed. Enrollment cannot be processed.'
            ], 422);
        }

        try {
            $studentCourse = StudentCourse::create([
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'student_payment_id' => $request->payment_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student course enrollment saved successfully!',
                'data' => $studentCourse
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(StudentCourse $studentCourse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($studentcourseId)
    {
        $studentcourse = StudentCourse::findOrFail($studentcourseId);
        $student = Students::pluck('name', 'id');
        $course = Course::pluck('name', 'id');
        $studentpayment = StudentPayment::pluck('transaction_id', 'id');
        return view('studentcourse.edit', compact('student', 'course', 'studentpayment', 'studentcourse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $studentcourseId)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'payment_id' => 'required|exists:student_payments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $studentCourse = StudentCourse::findOrFail($studentcourseId);

        $payment = StudentPayment::find($request->payment_id);
        if (!$payment || $payment->payment_status !== 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment is not completed. Enrollment cannot be updated.'
            ], 422);
        }

        try {
            $studentCourse->update([
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'student_payment_id' => $request->payment_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student course enrollment updated successfully!',
                'data' => $studentCourse
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($studentcourseId)
    // { {
    //         try {
    //             $studentCourse = StudentCourse::destroy($studentcourseId);
    //             return ['status' => 'success', 'message' => 'Course Enrollments deleted successfully!'];
    //         } catch (\Throwable $e) {
    //             return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //         }
    //     }
    // }

    public function destroy($id)
    {

        try {
            $data = StudentCourse::findOrFail($id);
            if ($data) {
                StudentCourse::find($id)->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => $data->name . ' Deleted successfully!',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function status($id)
    {
        try {
            $studentCourse = StudentCourse::findOrFail($id);
            if ($studentCourse) {
                $studentCourse->status = $studentCourse->status == 1 ? 0 : 1;
                $studentCourse->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $studentCourse->name . ' status updated successfully!',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'AdmissionType not found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}

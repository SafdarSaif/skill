<?php

namespace App\Http\Controllers;

use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class StudentProgressController extends Controller
{


    // Store or update student progress
    public function updateProgress(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'student_id' => 'required|integer',
                'video_id' => 'required|integer',
                'subject_id' => 'required|integer',
                'course_id' => 'required|integer',
                'subject_name' => 'required|string',
                'total_duration' => 'required|numeric|min:1',
                'watch_time' => 'required|numeric|min:0'
            ]);

            $progress = ($validatedData['watch_time'] / $validatedData['total_duration']) * 100;

            $progressRecord = StudentProgress::updateOrCreate(
                [
                    'student_id' => $validatedData['student_id'],
                    'video_id' => $validatedData['video_id']
                ],
                [
                    'subject_id' => $validatedData['subject_id'],
                    'course_id' => $validatedData['course_id'],
                    'subject_name' => $validatedData['subject_name'],
                    'total_duration' => $validatedData['total_duration'],
                    'watch_time' => $validatedData['watch_time'],
                    'progress' => round($progress, 2) 
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Progress updated successfully!',
                'progress' => round($progress, 2) . '%',
                'data' => $progressRecord
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage()
            ], 500);
        }
    }

    // Get student progress
    public function getProgress(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'student_id' => 'required|integer',
                'video_id' => 'required|integer'
            ]);

            $progress = StudentProgress::where([
                'student_id' => $validatedData['student_id'],
                'video_id' => $validatedData['video_id']
            ])->first();

            if (!$progress) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No progress found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $progress
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage()
            ], 500);
        }
    }






    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentProgress::with(['student', 'course'])
                ->orderBy('id', 'desc')
                ->get();
// dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('student_name', function ($data) {
                    return $data->student ? $data->student->name : 'N/A';
                })
                ->addColumn('course', function ($data) {
                    return $data->course ? $data->course->name : 'N/A';
                })
               
                ->editColumn('progress', function ($data) {
                    return $data->progress !== null ? $data->progress . '%' : '0%';
                })
                
                ->editColumn('status', function ($data) {
                    return $data->status ? 'Active' : 'Inactive';
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }

        return view('studentprogress.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentProgress $studentProgress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentProgress $studentProgress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentProgress $studentProgress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentProgress $studentProgress)
    {
        //
    }
}

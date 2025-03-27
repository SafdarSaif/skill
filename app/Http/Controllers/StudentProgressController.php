<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentCourse;
use App\Models\Subject;

class StudentProgressController extends Controller
{




    public function getStudentProgress($student_id, $subject_id, $video_id = null)
    {
        try {
            $query = StudentProgress::where('student_id', $student_id)
                ->where('subject_id', $subject_id);

            if ($video_id) {
                $query->where('video_id', $video_id);
            }

            $progressRecords = $query->get();

            if ($progressRecords->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'No progress found']);
            }

            $progressData = $progressRecords->map(function ($record) {
                return [
                    'video_id' => $record->video_id,
                    'subject_id' => $record->subject_id,
                    'subject_name' => $record->subject_name,
                    'course_id' => $record->course_id,
                    'total_duration' => $record->total_duration,
                    'watch_time' => $record->watch_time,
                    'progress' => round($record->progress, 2),
                    'status' => $record->progress >= 90 ? 'Completed' : 'Ongoing'
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $progressData
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }





    // public function updateProgress(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'student_id' => 'required|integer',
    //             'video_id' => 'required|integer',
    //             'subject_id' => 'required|integer',
    //             'course_id' => 'required|integer',
    //             'total_duration' => 'required|numeric|min:1',
    //             'watch_time' => 'required|numeric|min:0'
    //         ]);

    //         // Fetch subject using Eloquent
    //         $subject = Subject::find($validatedData['subject_id']);
    //         if (!$subject) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid subject ID!'
    //             ], 400);
    //         }

    //         // Fetch course using Eloquent
    //         $course = StudentCourse::find($validatedData['course_id']);
    //         if (!$course) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid course ID!'
    //             ], 400);
    //         }

    //         $progress = ($validatedData['watch_time'] / $validatedData['total_duration']) * 100;

    //         $progressRecord = StudentProgress::updateOrCreate(
    //             [
    //                 'student_id' => $validatedData['student_id'],
    //                 'video_id' => $validatedData['video_id']
    //             ],
    //             [
    //                 'subject_id' => $validatedData['subject_id'],
    //                 'course_id' => $validatedData['course_id'],
    //                 'subject_name' => $subject->name,
    //                 'total_duration' => $validatedData['total_duration'],
    //                 'watch_time' => $validatedData['watch_time'],
    //                 'progress' => round($progress, 2)
    //             ]
    //         );

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Progress updated successfully!',
    //             'progress' => round($progress, 2) . '%',
    //             'course_name' => $course->name,
    //             'subject_name' => $subject->name, // Use subject name from the model
    //             'data' => $progressRecord
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong! ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function updateProgress(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'student_id' => 'required|integer',
                'video_id' => 'required|integer',
                'subject_id' => 'required|integer',
                'course_id' => 'required|integer',
                'total_duration' => 'required|numeric|min:1',
                'watch_time' => 'required|numeric|min:0'
            ]);

            // Fetch subject using Eloquent
            $subject = Subject::find($validatedData['subject_id']);
            if (!$subject) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid subject ID!'
                ], 400);
            }

            // Fetch course using Eloquent
            $course = StudentCourse::find($validatedData['course_id']);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid course ID!'
                ], 400);
            }

            // Check if progress record exists
            $progressRecord = StudentProgress::where([
                'student_id' => $validatedData['student_id'],
                'video_id' => $validatedData['video_id']
            ])->first();

            // If already completed, do not update
            if ($progressRecord && $progressRecord->progress_status === 'completed') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Progress already completed. No update needed.',
                    'progress' => $progressRecord->progress . '%',
                    'progress_status' => $progressRecord->progress_status
                ], 200);
            }

            $progress = ($validatedData['watch_time'] / $validatedData['total_duration']) * 100;

            $progressStatus = $progress >= 90 ? 'completed' : 'not completed';

            $progressRecord = StudentProgress::updateOrCreate(
                [
                    'student_id' => $validatedData['student_id'],
                    'video_id' => $validatedData['video_id']
                ],
                [
                    'subject_id' => $validatedData['subject_id'],
                    'course_id' => $validatedData['course_id'],
                    'subject_name' => $subject->name,
                    'total_duration' => $validatedData['total_duration'],
                    'watch_time' => $validatedData['watch_time'],
                    'progress' => round($progress, 2),
                    'progress_status' => $progressStatus
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Progress updated successfully!',
                'progress' => round($progress, 2) . '%',
                'progress_status' => $progressStatus,
                'course_name' => $course->name,
                'subject_name' => $subject->name,
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
             $data = StudentProgress::with(['student', 'course', 'subject', 'video'])
                 ->orderBy('id', 'desc')
                 ->get();
     
                //  dd($data->toArray()); // Debugging step
             return DataTables::of($data)
                 ->addIndexColumn()
                 ->addColumn('student_name', function ($data) {
                     return optional($data->student)->name ?? 'N/A';
                 })
                 ->addColumn('course_name', function ($data) {
                     return optional($data->course)->name ?? 'N/A';
                    //  dd($data->course);
                 })
                 ->addColumn('subject_name', function ($data) {
                     return optional($data->subject)->name ?? 'N/A';
                 })
                 ->addColumn('video_name', function ($data) {
                     return optional($data->video)->name?? 'N/A';
                 })
                 ->editColumn('progress', function ($data) {
                     return isset($data->progress) ? $data->progress . '%' : '0%';
                 })
                 ->editColumn('progress_status', function ($data) {
                     return ucfirst($data->progress_status ?? 'N/A');
                 })
                 ->editColumn('created_at', function ($data) {
                     return $data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('d-m-Y h:i A') : 'N/A';
                 })
                 ->make(true);
         }
     
         return view('studentprogress.index');
     }
     

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = StudentProgress::with(['student', 'course'])
    //             ->orderBy('id', 'desc')
    //             ->get();
    //         // dd($data);
    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('student_name', function ($data) {
    //                 return $data->student ? $data->student->name : 'N/A';
    //             })
    //             ->addColumn('course', function ($data) {
    //                 return $data->course ? $data->course->name : 'N/A';
    //             })

    //             ->editColumn('progress', function ($data) {
    //                 return $data->progress !== null ? $data->progress . '%' : '0%';
    //             })

    //             ->editColumn('status', function ($data) {
    //                 return $data->status ? 'Active' : 'Inactive';
    //             })
    //             ->editColumn('created_at', function ($data) {
    //                 return Carbon::parse($data->created_at)->format('d-m-Y h:i A');
    //             })
    //             ->make(true);
    //     }

    //     return view('studentprogress.index');
    // }


    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = StudentProgress::with(['student', 'course'])
    //             ->orderBy('id', 'desc')
    //             ->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('student_name', function ($data) {
    //                 return $data->student ? $data->student->name : 'N/A';
    //             })
    //             ->addColumn('course', function ($data) {
    //                 return $data->course ? $data->course->name : 'N/A';
    //             })
    //             ->addColumn('subjects', function ($data) {
    //                 // Get subjects for the same course_id
    //                 $subjects = Subject::where('course_id', $data->course_id)->pluck('name')->toArray();
    //                 return implode(', ', $subjects);
    //             })
    //             ->addColumn('total_duration', function ($data) {
    //                 // Get total duration of all videos under the same course_id
    //                 $totalDuration = StudentProgress::where('course_id', $data->course_id)->sum('total_duration');
    //                 return $totalDuration ? gmdate("H:i:s", $totalDuration) : '0:00:00';
    //             })
    //             ->addColumn('watch_time', function ($data) {
    //                 // Get total watch time for all subjects under the same course_id
    //                 $watchTime = StudentProgress::where('course_id', $data->course_id)->sum('watch_time');
    //                 return $watchTime ? gmdate("H:i:s", $watchTime) : '0:00:00';
    //             })
    //             ->addColumn('overall_progress', function ($data) {
    //                 // Get total duration and watch time for progress calculation
    //                 $totalDuration = StudentProgress::where('course_id', $data->course_id)->sum('total_duration');
    //                 $watchTime = StudentProgress::where('course_id', $data->course_id)->sum('watch_time');

    //                 if ($totalDuration > 0) {
    //                     $progress = ($watchTime / $totalDuration) * 100;
    //                     return round($progress, 2) . '%';
    //                 }
    //                 return '0%';
    //             })
    //             ->editColumn('status', function ($data) {
    //                 return $data->status ? 'Active' : 'Inactive';
    //             })
    //             ->editColumn('created_at', function ($data) {
    //                 return Carbon::parse($data->created_at)->format('d-m-Y h:i A');
    //             })
    //             ->make(true);
    //     }

    //     return view('studentprogress.index');
    // }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = StudentProgress::selectRaw("
    //             course_id,
    //             MAX(student_id) as student_id, 
    //             MAX(subject_name) as subject_name, 
    //             SUM(total_duration) as total_duration, 
    //             SUM(watch_time) as watch_time,
    //             (SUM(watch_time) / NULLIF(SUM(total_duration), 0)) * 100 as progress,
    //             MAX(created_at) as created_at
    //         ")
    //             ->with(['student', 'course'])
    //             ->groupBy('course_id')
    //             ->orderBy('id', 'desc')
    //             ->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('student_name', function ($data) {
    //                 return $data->student ? $data->student->name : 'N/A';
    //             })
    //             ->addColumn('course', function ($data) {
    //                 return $data->course ? $data->course->name : 'N/A';
    //             })
    //             ->addColumn('subjects', function ($data) {
    //                 // Get subjects for the same course_id
    //                 $subjects = Subject::where('course_id', $data->course_id)->pluck('name')->toArray();
    //                 return implode(', ', $subjects);
    //             })
    //             ->addColumn('total_duration', function ($data) {
    //                 return gmdate("H:i:s", $data->total_duration);
    //             })
    //             ->addColumn('watch_time', function ($data) {
    //                 return gmdate("H:i:s", $data->watch_time);
    //             })
    //             ->addColumn('overall_progress', function ($data) {
    //                 return round($data->progress, 2) . '%';
    //             })
    //             ->editColumn('created_at', function ($data) {
    //                 return Carbon::parse($data->created_at)->format('d-m-Y h:i A');
    //             })
    //             ->make(true);
    //     }

    //     return view('studentprogress.index');
    // }


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

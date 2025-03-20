<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use App\Http\Controllers\OTPController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;




class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Students::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('students.index');
    }
    /**
     * Get all course wise subjects and subject wise e-books, notes, videos API
     */



    //  API buit by KP
    // public function getStudentDetails($mobile)
    // {
    //     try {
    //         $student = Students::where('mobile', $mobile)->get();
    //         if ($student->isEmpty()) {
    //             //modification get all enrolled courses and show which courses is completed or which are not completed with subject wise videos map with student progess
    //             return response()->json(['status' => 'error', 'message' => 'No student found with this mobile number']);
    //         }
    //         // $student['on_going_courses'] = 34;
    //         // $student['completed_courses'] = 12;
    //         return response()->json(['status' => 'success', 'data' => $student]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //     }
    // }




    public function getStudentDetails($mobile)
    {
        try {
            $student = Students::where('mobile', $mobile)->with('progress')->first();

            if (!$student) {
                return response()->json(['status' => 'error', 'message' => 'No student found with this mobile number']);
            }

            $onGoingCourses = [];
            $completedCourses = [];

            foreach ($student->progress as $course) {
                $courseDetails = [
                    'course_id' => $course->course_id,
                    'subject_id' => $course->subject_id,
                    'subject_name' => $course->subject_name,
                    'progress' => round($course->progress, 2),
                    'watch_time' => $course->watch_time,
                    'total_duration' => $course->total_duration,
                    'status' => $course->progress >= 90 ? 'Completed' : 'Ongoing'
                ];

                if ($course->progress >= 90) {
                    $completedCourses[] = $courseDetails;
                } else {
                    $onGoingCourses[] = $courseDetails;
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'student' => $student,
                    'on_going_courses' => $onGoingCourses,
                    'completed_courses' => $completedCourses
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    // public function getStudentDetails($mobile)
    // {
    //     try {
    //         $student = Students::where('mobile', $mobile)->first();

    //         if (!$student) {
    //             return response()->json(['status' => 'error', 'message' => 'No student found with this mobile number']);
    //         }


    //         $courses = DB::table('student_progress')
    //             ->where('student_id', $student->id)
    //             ->select(
    //                 'course_id',
    //                 'subject_id',
    //                 'subject_name',
    //                 DB::raw('SUM(watch_time) as total_watch_time'),
    //                 DB::raw('SUM(total_duration) as total_video_duration'),
    //                 DB::raw('AVG(progress) as avg_progress')
    //             )
    //             ->groupBy('course_id', 'subject_id', 'subject_name')
    //             ->get();

    //         $onGoingCourses = [];
    //         $completedCourses = [];

    //         foreach ($courses as $course) {
    //             $courseDetails = [
    //                 'course_id' => $course->course_id,
    //                 'subject_id' => $course->subject_id,
    //                 'subject_name' => $course->subject_name,
    //                 'progress' => round($course->avg_progress, 2),
    //                 'watch_time' => $course->total_watch_time,
    //                 'total_duration' => $course->total_video_duration,
    //                 'status' => $course->avg_progress >= 90 ? 'Completed' : 'Ongoing'
    //             ];

    //             if ($course->avg_progress >= 90) {
    //                 $completedCourses[] = $courseDetails;
    //             } else {
    //                 $onGoingCourses[] = $courseDetails;
    //             }
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => [
    //                 'student' => $student,
    //                 'on_going_courses' => $onGoingCourses,
    //                 'completed_courses' => $completedCourses
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //     }
    // }


    public static function StudentAllDetaills($mobile)
    {
        try {
            $student = Students::where('mobile', $mobile)->first();
            if ($student->count()) {
                $studata = Students::where('mobile', $mobile)->with('studentCourses')->first();
                return response()->json(['status' => 'success', 'data' => $studata]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'No student found with this mobile number']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function registerStudent(Request $request)
    {
        // dd($request);
        $mobile = $request->mobile;
        try {
            $student = Students::where('mobile',)->first();

            if ($student) {
                return response()->json(['status' => 'error', 'message' => 'Student already registered with this ' . $request->mobile]);
            }

            $studentdata = new Students();
            $studentdata->name = $request->name;
            $studentdata->email = $request->email;
            $studentdata->mobile = $mobile;
            $studentdata->status = 1;
            $studentdata->save();
            $otpresponse = OTPController::getOtp($mobile);

            return $otpresponse;
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }


    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:students,email',
            'dob' => 'required|date',
            'mobile' => 'required|digits:10|unique:students,mobile',
            'fathers_name' => 'required|string|max:255',
            'mothers_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'state' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|digits:6',
            'country' => 'required|string',
            'highest_qualification' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
        ]);
        dd($request->all());
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage($request->file('image'), 'students/images');
            }

            // Handle signature upload
            $signaturePath = null;
            if ($request->hasFile('signature')) {
                $signaturePath = $this->uploadImage($request->file('signature'), 'students/signatures');
            }

            // Create a new student record
            $student = Students::create([
                'name' => $request->name,
                'email' => $request->email,
                'dob' => $request->dob,
                'mobile' => $request->mobile,
                'fathers_name' => $request->fathers_name,
                'mothers_name' => $request->mothers_name,
                'address' => $request->address,
                'state' => $request->state,
                'district' => $request->district,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'country' => $request->country,
                'highest_qualification' => $request->highest_qualification,
                'image' => $imagePath,
                'signature' => $signaturePath,
                'status' => 1, // Default active status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student added successfully!',
                'data' => $student
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Students::findOrFail($id);
        return view('students.profile', compact('student'));
    }

    public function profile($id)
    {
        $student = Students::with('studentCourses.course')->findOrFail($id);

        return view('students.profile', compact('student'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Students $students)
    // {
    //     //
    // }
    public function edit($studentId)
    {
        $student = Students::findOrFail($studentId);

        return view('students.edit', compact('student'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $studentId)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $studentId,
            'dob' => 'required|date',
            'mobile' => 'required|digits:10|unique:students,mobile,' . $studentId,
            'fathers_name' => 'required|string|max:255',
            'mothers_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'state' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|digits:6',
            'country' => 'required|string',
            'highest_qualification' => 'required|string', // Fixed typo
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
        ]);

        try {
            // Find student record
            $student = Students::findOrFail($studentId);

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage($request->file('image'), 'students/images');
                $student->image = $imagePath;
            }

            // Handle signature upload
            if ($request->hasFile('signature')) {
                $signaturePath = $this->uploadImage($request->file('signature'), 'students/signatures');
                $student->signature = $signaturePath;
            }

            // Update student details
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'dob' => $request->dob,
                'mobile' => $request->mobile,
                'fathers_name' => $request->fathers_name,
                'mothers_name' => $request->mothers_name,
                'address' => $request->address,
                'state' => $request->state,
                'district' => $request->district,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'country' => $request->country,
                'highest_qualification' => $request->highest_qualification,
                'image' => $imagePath,
                'signature' => $signaturePath,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student updated successfully!',
                'data' => $student
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


         // API form KP
    // public function updateStudents(Request $request)
    // {

    //     try {
    //         $imagePath = null;
    //         if ($request->hasFile('image')) {
    //             $imagePath = $this->uploadImage($request->file('image'), 'students/images');
    //         }
    //         $signaturePath = null;
    //         if ($request->hasFile('signature')) {
    //             $signaturePath = $this->uploadImage($request->file('signature'), 'students/signatures');
    //         }

    //         $data = [
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'dob' => $request->dob,
    //             'mobile' => $request->mobile,
    //             'fathers_name' => $request->fathers_name,
    //             'mothers_name' => $request->mothers_name,
    //             'address' => $request->address,
    //             'state' => $request->state,
    //             'district' => $request->district,
    //             'city' => $request->city,
    //             'pincode' => $request->pincode,
    //             'country' => $request->country,
    //             'heighest_qualification' => $request->heighest_qualification,
    //         ];
    //         $student = Students::findOrFail($request->id);

    //         if ($student) {
    //             $student->update($data);
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Student updated successfully!',
    //                 'data' => $data
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Student Not Found!',
    //                 'data' => $request->all()
    //             ], 200);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function updateStudents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:students,id',
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email|unique:students,email,' . $request->id,
                'dob' => 'required|date',
                'mobile' => 'required|digits:10|unique:students,mobile,' . $request->id,
                'fathers_name' => 'required|string|max:255',
                'mothers_name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'state' => 'required|string',
                'district' => 'required|string',
                'city' => 'required|string',
                'pincode' => 'required|digits:6',
                'country' => 'required|string',
                'highest_qualification' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $student = Students::find($request->id);

            if (!$student) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Student Not Found!',
                ], 404);
            }

            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage($request->file('image'), 'students/images');
                $student->image = $imagePath;
            }

            if ($request->hasFile('signature')) {
                $signaturePath = $this->uploadImage($request->file('signature'), 'students/signatures');
                $student->signature = $signaturePath;
            }

            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'dob' => $request->dob,
                'mobile' => $request->mobile,
                'fathers_name' => $request->fathers_name,
                'mothers_name' => $request->mothers_name,
                'address' => $request->address,
                'state' => $request->state,
                'district' => $request->district,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'country' => $request->country,
                'highest_qualification' => $request->highest_qualification,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student updated successfully!',
                'data' => $student
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($studentId)
    { {
            try {
                $student = Students::destroy($studentId);
                return ['status' => 'success', 'message' => 'Student deleted successfully!'];
            } catch (\Throwable $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
    public function status($id)
    {
        try {
            $student = Students::findOrFail($id);
            if ($student) {
                $student->status = $student->status == 1 ? 0 : 1;
                $student->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $student->name . ' status updated successfully!',
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

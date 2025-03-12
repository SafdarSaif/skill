<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use App\Http\Controllers\OTPController;
use Illuminate\Support\Facades\Validator;



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

    public function getStudentDetails($mobile)
    {
        try {
            $student = Students::where('mobile', $mobile)->get();
            if ($student->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'No student found with this mobile number']);
            }
            return response()->json(['status' => 'success', 'data' => $student]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

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
            $student = Students::where('mobile', )->first();

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

    // public function store(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|email|unique:students,email',
    //             'dob' => 'required|date',
    //             'mobile' => 'required|digits:10|unique:students,mobile',
    //             'fathers_name' => 'required|string|max:255',
    //             'mothers_name' => 'required|string|max:255',
    //             'address' => 'required|string|max:500',
    //             'state' => 'required|string',
    //             'district' => 'required|string',
    //             'city' => 'required|string',
    //             'pincode' => 'required|digits:6',
    //             'country' => 'required|string',
    //             'heighest_qualification' => 'required|string',
    //         ]);

    //         // Merge the request data with status = 1
    //         $data = $request->all();
    //         $data['status'] = 1;

    //         Students::create($data);

    //         return response()->json(['status' => 'success', 'message' => 'Student added successfully!'], 201);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong. Please try again.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

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

    public function updateStudents(Request $request)
    {
 
        try {
            $data = [
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
                'heighest_qualification' => $request->heighest_qualification,
            ];
            $student = Students::findOrFail($request->id);
            if ($student) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Student Not Found!',
                    'data' =>$request->all()
                ], 200);
            }
            $student->update($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Student updated successfully!',
                'data' => $data
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

<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

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
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:students,email',
    //         'dob' => 'required|date',
    //         'mobile' => 'required|digits:10|unique:students,mobile',
    //         'fathers_name' => 'required|string|max:255',
    //         'mothers_name' => 'required|string|max:255',
    //         'address' => 'required|string|max:500',
    //         'state' => 'required|string',
    //         'district' => 'required|string',
    //         'city' => 'required|string',
    //         'pincode' => 'required|digits:6',
    //         'country' => 'required|string',
    //         'heighest_qualification' => 'required|string',
    //         'status' => 'required|boolean',
    //     ]);

    //     Students::create($request->all());

    //     return response()->json(['status' => 'success', 'message' => 'Student added successfully!']);
    // }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
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
                'heighest_qualification' => 'required|string',
            ]);

            // Merge the request data with status = 1
            $data = $request->all();
            $data['status'] = 1;

            Students::create($data);

            return response()->json(['status' => 'success', 'message' => 'Student added successfully!'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(), // Returns errors in the required format
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Students $students)
    {
        //
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
            'heighest_qualification' => 'required|string',
        ]);
    
        try {
            // Find student record
            $student = Students::findOrFail($studentId);
    
            // Update each field manually (instead of mass assignment)
            $student->name = $validated['name'];
            $student->email = $validated['email'];
            $student->dob = $validated['dob'];
            $student->mobile = $validated['mobile'];
            $student->fathers_name = $validated['fathers_name'];
            $student->mothers_name = $validated['mothers_name'];
            $student->address = $validated['address'];
            $student->state = $validated['state'];
            $student->district = $validated['district'];
            $student->city = $validated['city'];
            $student->pincode = $validated['pincode'];
            $student->country = $validated['country'];
            $student->heighest_qualification = $validated['heighest_qualification'];
            
            $student->save(); // Save the updated student data
    
            return response()->json(['status' => 'success', 'message' => 'Student updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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

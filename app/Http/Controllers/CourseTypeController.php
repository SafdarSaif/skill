<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Exception;


class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CourseType::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('coursemangement.coursetype.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('coursemangement.coursetype.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Create a new course type
            $courseType = CourseType::create([
                'name' => $request->name,

            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Course Type added successfully!',
                'data' => $courseType
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(CourseType $courseType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($coursetypeID)
    {
        $courseType = CourseType::findOrFail($coursetypeID);


        return view('coursemangement.coursetype.edit', compact('courseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $courseType = CourseType::findOrFail($id);

            $courseType->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Course Type updated successfully!',
                'data'    => $courseType
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($coursetypeID)
    { {
            try {
                $courseType = CourseType::destroy($coursetypeID);
                return ['status' => 'success', 'message' => 'Course Type  deleted successfully!'];
            } catch (\Throwable $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function status($id)
    {
        try {
            $courseType = CourseType::findOrFail($id);
            if ($courseType) {
                $courseType->status = $courseType->status == 1 ? 0 : 1;
                $courseType->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $courseType->name . ' status updated successfully!',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found',
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

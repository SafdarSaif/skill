<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Course::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('coursemangement.course.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::pluck('name', 'id');

        return view('coursemangement.course.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|string|max:100',
                'category_id' => 'required|exists:categories,id',
            ]);

            $data = $request->all();
            $data['status'] = 1;

            Course::create($data);

            return response()->json(['status' => 'success', 'message' => 'Course added successfully!'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
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
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($courseID)
    {
        $category = Category::pluck('name', 'id');
        $course = Course::findOrFail($courseID);


        return view('coursemangement.course.edit', compact('category', 'course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $courseID)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|string|max:100',
                'category_id' => 'required|exists:categories,id',
            ]);

            $course = Course::findOrFail($courseID);

            $course->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'duration' => $request->duration,
                'category_id' => $request->category_id,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Course updated successfully!'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
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
     * Remove the specified resource from storage.
     */
    public function destroy($courseID)
    { {
            try {
                $category = Category::destroy($courseID);
                return ['status' => 'success', 'message' => 'Course Category  deleted successfully!'];
            } catch (\Throwable $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function status($id)
    {
        try {
            $course = Course::findOrFail($id);
            if ($course) {
                $course->status = $course->status == 1 ? 0 : 1;
                $course->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $course->name . ' status updated successfully!',
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

<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;


class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Subject::with(['course'])
                ->orderBy('id', 'desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y h:i A') : 'N/A';
                })

                ->addColumn('course_name', function ($data) {
                    return $data->course ? $data->course->name : 'N/A';
                })
                ->make(true);
        }

        return view('subject.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $course = Course::pluck('name', 'id');


        return view('subject.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'course_id'   => 'required|exists:courses,id',
            'name'        => 'required|string|min:3|max:255|unique:subjects,name',
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'subject_images');
            }

            $subject = Subject::create([
                'course_id'   => $request->course_id,
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $imagePath,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Subject added successfully!',
                'data'    => $subject
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $course = Course::pluck('name', 'id');

        return view('subject.edit', compact('subject', 'course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $subjectId)
    {
        $validator = Validator::make($request->all(), [
            'course_id'   => 'required|exists:courses,id',
            'name'        => 'required|string|min:3|max:255|unique:subjects,name,' . $subjectId,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Image validation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $subject = Subject::findOrFail($subjectId);

        try {
            // Handle Image Upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($subject->image) {
                    deleteImage($subject->image); // Custom helper function to remove old image
                }
                // Upload new image
                $imagePath = uploadImage($request->file('image'), 'subject_images');
            } else {
                $imagePath = $subject->image; // Keep existing image if no new one is uploaded
            }

            // Update Subject
            $subject->update([
                'course_id'   => $request->course_id,
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $imagePath,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Subject updated successfully!',
                'data'    => $subject
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage()
            ], 500);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subjectId)
    { {
            try {
                $subject = Subject::destroy($subjectId);
                return ['status' => 'success', 'message' => 'Subject deleted successfully!'];
            } catch (\Throwable $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
    public function status($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            if ($subject) {
                $subject->status = $subject->status == 1 ? 0 : 1;
                $subject->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $subject->name . ' status updated successfully!',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Subject not found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    // public function getCourseSubjects()
    // {
    //     try {
    //         $data = Subject::with('course')->get()->toArray();
    //         if ($data) {
    //             return response()->json(['data' => $data, "status" => "success", "message" => "All Subject List"]);
    //         } else {
    //             return response()->json(['status' => "error", "message" => "Subject not found!"]);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ]);
    //     }

    // }



    public function getCourseSubjects(Request $request, $column = '', $value = '')
    {
        try {
            $query = Subject::with('course');

            if (!empty($column) && !empty($value)) {
                $query->where($column, $value);
            }

            $data = $query->get()->toArray();

            if (!empty($data)) {
                return response()->json(['status' => "success", 'message' => "All Subject List", "data" => $data]);
            } else {
                return response()->json(['status' => "error", 'message' => "No Subject Found"]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getVideoNotesBySubject($id)
    {
        try{
            $subjects = Subject::where('id',$id)->with('videos','notes')->get();
            return response()->json(['status'=>'success','data'=>$subjects]);
        }
        catch(Exception $e)
        {
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

}

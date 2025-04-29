<?php

namespace App\Http\Controllers;

use App\Models\SubjectNote;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Subject;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseType as Type;
use App\Models\Category;
use Illuminate\Support\Facades\Log;


class SubjectNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $id = $request->query('id');
        // dd($id);
        if ($request->ajax()) {
            $data = SubjectNote::with(['subject', 'user'])
                ->orderBy('id', 'desc');

            // If you want to filter based on ID, you can do:
            if ($id) {
                $data->where('subject_id', $id);
            }

            return DataTables::of($data->get())
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y h:i A') : 'N/A';
                })
                ->addColumn('subject_name', function ($data) {
                    return $data->subject ? $data->subject->name : 'N/A';
                })
                ->addColumn('user_name', function ($data) {
                    return $data->user ? $data->user->name : 'N/A';
                })
                ->make(true);
        }

        return view('subject.notes.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::where('status', 1)->pluck('name', 'id');
        $categories = Category::where('status', 1)->pluck('name', 'id');
        $courses = Course::where('status', 1)->pluck('name', 'id');
        $subjects = Subject::where('status', 1)->pluck('name', 'id');
        $users = User::where('status', 1)->pluck('name', 'id'); 

        return view('subject.notes.create', compact('subjects', 'users', 'courses', 'types', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id'     => 'required|exists:course_types,id',
            'category_id' => 'required|exists:categories,id',
            'course_id'   => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'user_id' => 'required|exists:users,id',
            'upload_type' => 'required|in:url,pdf',
            'note_link' => 'nullable|url|required_if:upload_type,url',
            'note_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:51200|required_if:upload_type,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $noteUrl = null;
            $filePath = null;

            if ($request->upload_type === 'url') {
                $noteUrl = $request->note_link;
            } elseif ($request->hasFile('note_file')) {
                $filePath = uploadFile($request->file('note_file'), 'subject_notes');
            }

            $subjectNote = SubjectNote::create([
                'type_id'      => $request->type_id,
                'category_id'  => $request->category_id,
                'course_id'    => $request->course_id,
                'subject_id' => $request->subject_id,
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $request->user_id,
                'upload_type' => $request->upload_type,
                'url' => $noteUrl,
                'file_path' => $filePath,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Subject note added successfully!',
                'data' => $subjectNote
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error adding subject note: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(SubjectNote $subjectNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($noteId)
    {
        $note = SubjectNote::findOrFail($noteId);
        $types = Type::where('status', 1)->pluck('name', 'id');
        $categories = Category::where('status', 1)->pluck('name', 'id');
        $courses = Course::where('status', 1)->pluck('name', 'id');
        $subjects = Subject::where('status', 1)->pluck('name', 'id');
        $users = User::where('status', 1)->pluck('name', 'id'); 
        return view('subject.notes.edit', compact('note', 'subjects', 'users', 'courses', 'types', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $noteId)
    {
        $validator = Validator::make($request->all(), [
            'type_id'     => 'required|exists:course_types,id',
            'category_id' => 'required|exists:categories,id',
            'course_id'   => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'user_id' => 'required|exists:users,id',
            'upload_type' => 'required|in:url,pdf',
            'note_link' => 'nullable|url|required_if:upload_type,url',
            'note_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:51200|required_if:upload_type,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subjectNote = SubjectNote::findOrFail($noteId);

            $noteUrl = $subjectNote->url;
            $filePath = $subjectNote->file_path;

            if ($request->upload_type === 'url') {
                $noteUrl = $request->note_link;
                $filePath = null;
            } elseif ($request->hasFile('note_file')) {
                if ($subjectNote->file_path) {
                    deleteFile('uploads/subject_notes/' . $subjectNote->file_path);
                }

                $filePath = uploadFile($request->file('note_file'), 'subject_notes');
                $noteUrl = null;
            }

            $subjectNote->update([
                'type_id'      => $request->type_id,
                'category_id'  => $request->category_id,
                'course_id'    => $request->course_id,
                'subject_id' => $request->subject_id,
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $request->user_id,
                'upload_type' => $request->upload_type,
                'url' => $noteUrl,
                'file_path' => $filePath,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Subject note updated successfully!',
                'data' => $subjectNote
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating subject note: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($noteId)
    // { 
    // try {
    //     $SubjectNote = SubjectNote::destroy($noteId);
    //     return ['status' => 'success', 'message' => 'Subject Note deleted successfully!'];
    // } catch (\Throwable $e) {
    //     return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    // }


    // }
    public function destroy($noteId)
    {
        try {
            $data = SubjectNote::findOrFail($noteId);
            if ($data) { 
                SubjectNote::find($noteId)->delete();
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
            $SubjectNote = SubjectNote::findOrFail($id);
            if ($SubjectNote) {
                $SubjectNote->status = $SubjectNote->status == 1 ? 0 : 1;
                $SubjectNote->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $SubjectNote->name . ' status updated successfully!',
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
}

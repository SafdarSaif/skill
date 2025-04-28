<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Subject;
use App\Models\User;
use App\Models\CourseType as Type;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Log;

class EbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = Ebook::with(['subject', 'user'])
    //             ->orderBy('id', 'desc')
    //             ->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->editColumn('created_at', function ($data) {
    //                 return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y h:i A') : 'N/A';
    //             })

    //             ->addColumn('subject_name', function ($data) {
    //                 return $data->course ? $data->course->name : 'N/A';
    //             })
    //             ->addColumn('user_name', function ($data) {
    //                 return $data->user ? $data->user->name : 'N/A';
    //             })
    //             ->make(true);
    //     }

    //     return view('subject.ebook.index');
    // }


    public function index(Request $request)
    {
        $subjectId = $request->query('id'); // optional filtering by subject_id

        if ($request->ajax()) {
            $query = Ebook::with(['subject', 'user'])
                ->orderBy('id', 'desc');

            if ($subjectId) {
                $query->where('subject_id', $subjectId);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y h:i A') : 'N/A';
                })
                ->addColumn('subject_name', function ($data) {
                    return $data->subject ? $data->subject->name : 'N/A'; // fixed from 'course' to 'subject'
                })
                ->addColumn('user_name', function ($data) {
                    return $data->user ? $data->user->name : 'N/A';
                })
                ->make(true);
        }

        return view('subject.ebook.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
        $courses = Course::pluck('name', 'id');
        $subjects = Subject::pluck('name', 'id');
        $users = User::pluck('name', 'id');

        return view('subject.ebook.create', compact('subjects', 'users', 'types', 'categories', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'subject_id'  => 'required|exists:subjects,id',
    //         'name'        => 'required|string|min:3|max:255',
    //         'description' => 'nullable|string|max:1000',
    //         'user_id'     => 'required|exists:users,id',
    //         'upload_type' => 'required|in:url,pdf', 
    //         'ebook_link'  => 'nullable|url|required_if:upload_type,url', 
    //         'ebook_file'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:51200|required_if:upload_type,pdf', 
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => $validator->errors()->first(),
    //             'errors'  => $validator->errors()
    //         ], 422);
    //     }

    //     try {
    //         $ebookUrl = null;
    //         $filePath = null;

    //         if ($request->upload_type === 'url') {
    //             $ebookUrl = $request->ebook_link;
    //         } elseif ($request->hasFile('ebook_file')) {
    //             $filePath = uploadFile($request->file('ebook_file'), 'ebooks');
    //         }

    //         $ebook = Ebook::create([
    //             'subject_id'  => $request->subject_id,
    //             'name'        => $request->name,
    //             'description' => $request->description,
    //             'user_id'     => $request->user_id,
    //             'upload_type' => $request->upload_type,
    //             'url'         => $ebookUrl,
    //             'file_path'   => $filePath, 
    //         ]);

    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'Ebook added successfully!',
    //             'data'    => $ebook
    //         ], 201);
    //     } catch (\Exception $e) {
    //         Log::error('Error adding ebook: ' . $e->getMessage(), [
    //             'request' => $request->all(),
    //             'trace'   => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Something went wrong. Please try again later.',
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id'     => 'required|exists:course_types,id',
            'category_id' => 'required|exists:categories,id',
            'course_id'   => 'required|exists:courses,id',
            'subject_id'  => 'required|exists:subjects,id',
            'name'        => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'user_id'     => 'required|exists:users,id',
            'upload_type' => 'required|in:url,pdf',
            'ebook_link'  => 'nullable|url|required_if:upload_type,url',
            'ebook_file'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:51200|required_if:upload_type,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $externalLink = null;
            $fileLocation = null;

            if ($request->upload_type === 'url') {
                $externalLink = $request->ebook_link;
            } elseif ($request->hasFile('ebook_file')) {
                $fileLocation = uploadFile($request->file('ebook_file'), 'ebooks');
            }

            $ebook = Ebook::create([
                'type_id'      => $request->type_id,
                'category_id'  => $request->category_id,
                'course_id'    => $request->course_id,
                'subject_id'   => $request->subject_id,
                'name'         => $request->name,
                'description'  => $request->description,
                'user_id'      => $request->user_id,
                'upload_type'  => $request->upload_type,
                'external_link' => $externalLink,
                'file_location' => $fileLocation,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Ebook added successfully!',
                'data'    => $ebook
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error adding ebook: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Ebook $ebook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ebookId)
    {
        $ebook = Ebook::findOrFail($ebookId);
        $types = Type::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
        $courses = Course::pluck('name', 'id');
        $subjects = Subject::pluck('name', 'id');

        $users = User::pluck('name', 'id');
        return view('subject.ebook.edit', compact('ebook', 'subjects', 'users', 'types', 'categories', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ebookId)
    {
        $validator = Validator::make($request->all(), [
            'type_id'     => 'required|exists:course_types,id',
            'category_id' => 'required|exists:categories,id',
            'course_id'   => 'required|exists:courses,id',
            'subject_id'  => 'required|exists:subjects,id',
            'name'        => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'user_id'     => 'required|exists:users,id',
            'upload_type' => 'required|in:url,pdf',
            // 'ebook_link'  => 'nullable|url|required_if:upload_type,url',
            // 'ebook_file'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:51200|required_if:upload_type,pdf',
            'ebook_link'  => 'nullable|url',
            'ebook_file'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $ebook = Ebook::findOrFail($ebookId);

            $ebookUrl = $ebook->external_link;
            $filePath = $ebook->file_location;

            if ($request->upload_type === 'url') {
                $ebookUrl = $request->ebook_link;
                $filePath = null;
            } elseif ($request->hasFile('ebook_file')) {
                if ($ebook->file_location) {
                    deleteFile('uploads/ebooks/' . $ebook->file_location);
                }

                $filePath = uploadFile($request->file('ebook_file'), 'ebooks');
                $ebookUrl = null;
            }

            $ebook->update([
                'type_id'      => $request->type_id,
                'category_id'  => $request->category_id,
                'course_id'    => $request->course_id,
                'subject_id'  => $request->subject_id,
                'name'        => $request->name,
                'description' => $request->description,
                'user_id'     => $request->user_id,
                'upload_type' => $request->upload_type,
                'external_link' => $ebookUrl,
                'file_location' => $filePath,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Ebook updated successfully!',
                'data'    => $ebook
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating ebook: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($ebookId)
    // { {
    //         try {
    //             $ebook = Ebook::destroy($ebookId);
    //             return ['status' => 'success', 'message' => 'Ebook  deleted successfully!'];
    //         } catch (\Throwable $e) {
    //             return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //         }
    //     }
    // }

    public function destroy($id)
    {
        try {
            $data = Ebook::findOrFail($id);
            if ($data) {
                Ebook::find($id)->delete();
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
            $ebook = Ebook::findOrFail($id);
            if ($ebook) {
                $ebook->status = $ebook->status == 1 ? 0 : 1;
                $ebook->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $ebook->name . ' status updated successfully!',
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

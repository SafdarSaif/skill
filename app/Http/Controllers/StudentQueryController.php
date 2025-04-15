<?php

namespace App\Http\Controllers;

use App\Models\StudentQuery;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Students;
use App\Models\SubjectVideo;

class StudentQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         // $data = StudentQuery::orderBy('id', 'desc')->get();
    //         $data = StudentQuery::with('student')->orderBy('id', 'desc')->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('name', function ($row) {
    //                 return $row->student ? $row->student->name : 'N/A';
    //             })
    //             ->editColumn('created_at', function ($data) {
    //                 return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
    //             })
    //             ->make(true);
    //     }
    //     return view('website.studentquery.index');
    // }


    // New Flow 


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentQuery::with('student')
                ->orderBy('id', 'desc')
                ->get()
                ->unique('student_id') // Keep only one query per student
                ->values(); // Reset keys

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->student ? $row->student->name : 'N/A';
                })
                ->addColumn('email', function ($row) {
                    return $row->student ? $row->student->email : 'N/A';
                })
                ->addColumn('query', function ($row) {
                    return $row->query ?? 'N/A';
                })
                ->addColumn('attachment', function ($row) {
                    return $row->attachment;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }

        return view('website.studentquery.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $student = Students::pluck('name', 'id');
        $subjectvideo = SubjectVideo::pluck('name', 'id');

        return view('website.studentquery.create', compact('student', 'subjectvideo'));
    }

    /**
     * Store a newly created resource in storage.
     */

    // public function store(Request $request)
    // {

    //     // Validate the request data
    //     $validator = Validator::make($request->all(), [
    //         'video_id' => 'required|exists:subject_videos,id',
    //         'student_id' => 'required|exists:students,id',
    //         'student_name' => 'required|string|min:3|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone' => 'required|digits:10',
    //         'query' => 'required|string|min:10',
    //         'answer' => 'nullable|string',
    //         'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     try {
    //         $attachmentPath = $request->hasFile('attachment')
    //             ? uploadFile($request->file('attachment'), 'attachments')
    //             : null;


    //         $studentQuery = StudentQuery::create([
    //             'video_id' => $request->input('video_id'),
    //             'student_id' => $request->input('student_id'),
    //             'name' => $request->input('student_name'),
    //             'email' => $request->input('email'),
    //             'phone' => $request->input('phone'),
    //             'query' => $request->input('query'),
    //             'answer' => $request->input('answer', null),
    //             'attachment' => $attachmentPath,
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Student query added successfully!',
    //             'data' => $studentQuery
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Failed to add student query: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_id' => 'required|exists:subject_videos,id',
            'student_id' => 'required|exists:students,id',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits:10',
            'query' => 'required|string',
            'answer' => 'nullable|string',
            'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Allow multiple files
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $attachmentPath = ['answer' => []];

            if ($request->hasFile('attachment')) {
                $index = 0;
                foreach ($request->file('attachment') as $file) {
                    $filePath = uploadFile($file, 'attachments');
                    $attachmentPath['answer'][(string) $index] = $filePath;
                    $index++;
                }
            }

            $studentQuery = StudentQuery::create([
                'video_id' => $request->input('video_id'),
                'student_id' => $request->input('student_id'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'query' => $request->input('query'),
                'answer' => $request->input('answer', null),
                'attachment' => !empty($attachmentPath['answer']) ? json_encode($attachmentPath, JSON_FORCE_OBJECT) : null, // Ensure forced JSON object format
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student query added successfully!',
                'data' => $studentQuery
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add student query: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    // Api for student query
    // public function getQuery(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'video_id' => 'required|exists:subject_videos,id',
    //         'student_id' => 'required|exists:students,id',
    //         'student_name' => 'required|string|min:3|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone' => 'required|digits:10',
    //         'query' => 'required|string|min:10',
    //         'answer' => 'nullable|string',
    //         'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     try {
    //         $attachmentPath = $request->hasFile('attachment')
    //             ? uploadFile($request->file('attachment'), 'attachments')
    //             : null;


    //         $studentQuery = StudentQuery::create([
    //             'video_id' => $request->input('video_id'),
    //             'student_id' => $request->input('student_id'),
    //             'name' => $request->input('student_name'),
    //             'email' => $request->input('email'),
    //             'phone' => $request->input('phone'),
    //             'query' => $request->input('query'),
    //             'answer' => $request->input('answer', null),
    //             'attachment' => $attachmentPath,
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Student query added successfully!',
    //             'data' => $studentQuery
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Failed to add student query: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function getQuery(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'video_id' => 'required|exists:subject_videos,id',
            'student_id' => 'required|exists:students,id',
            'student_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits:10',
            'query' => 'required|string',
            'answer' => 'nullable|string',
            'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Allow multiple files
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $attachmentPath = ['question' => []];

            if ($request->hasFile('attachment')) {
                $index = 0;
                foreach ($request->file('attachment') as $file) {
                    $filePath = uploadFile($file, 'attachments');
                    $attachmentPath['question'][(string) $index] = $filePath;
                    $index++;
                }
            }

            $studentQuery = StudentQuery::create([
                'video_id' => $request->input('video_id'),
                'student_id' => $request->input('student_id'),
                'name' => $request->input('student_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'query' => $request->input('query'),
                'answer' => $request->input('answer', null),
                'attachment' => json_encode($attachmentPath, JSON_FORCE_OBJECT),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student query added successfully!',
                'data' => $studentQuery
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add student query: ' . $e->getMessage()
            ], 500);
        }
    }



    // public function sndResponse(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'video_id' => 'required|exists:subject_videos,id',
    //         'student_id' => 'required|exists:students,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     try {
    //         $queries = StudentQuery::where('video_id', $request->video_id)
    //             ->where('student_id', $request->student_id)
    //             ->get()
    //             ->map(function ($query) {
    //                 return [
    //                     'id' => $query->id,
    //                     'video_id' => $query->video_id,
    //                     'student_id' => $query->student_id,
    //                     'name' => $query->name,
    //                     'email' => $query->email,
    //                     'phone' => $query->phone,
    //                     'query' => $query->query,
    //                     'answer' => $query->answer,
    //                     'attachments' => $query->attachment ? explode(',', $query->attachment) : [], // Convert to array
    //                     'status' => $query->status,
    //                     'created_at' => $query->created_at,
    //                     'updated_at' => $query->updated_at
    //                 ];
    //             });

    //         if ($queries->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'No queries found for this student and video.',
    //             ], 404);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Student queries retrieved successfully!',
    //             'data' => $queries
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Failed to retrieve queries: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function sndResponse($student_id, $video_id)
    {
        try {
            $validator = Validator::make(
                ['student_id' => $student_id, 'video_id' => $video_id],
                [
                    'video_id' => 'required|exists:subject_videos,id',
                    'student_id' => 'required|exists:students,id',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $queries = StudentQuery::where('video_id', $video_id)
                ->where('student_id', $student_id)
                ->get()
                ->map(function ($query) {
                    return [
                        'id' => $query->id,
                        'video_id' => $query->video_id,
                        'student_id' => $query->student_id,
                        'name' => $query->name,
                        'email' => $query->email,
                        'phone' => $query->phone,
                        'query' => $query->query,
                        'answer' => $query->answer,
                        'attachments' => $query->attachment ? json_decode($query->attachment, true) : [], // Proper JSON decoding
                        'status' => $query->status,
                        'created_at' => $query->created_at,
                        'updated_at' => $query->updated_at
                    ];
                });

            if ($queries->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No queries found for this student and video.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Student queries retrieved successfully!',
                'data' => $queries
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve queries: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    // public function show(StudentQuery $studentQuery)
    // {
    //     //
    // }
    public function show($id, $studentId)
    {
        $student = Students::findOrFail($studentId);

        // Group by video_id and aggregate total queries per video
        $videoQueries = StudentQuery::with('video')
            ->where('student_id', $studentId)
            ->get()
            ->groupBy('video_id')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'id' => $first->id,
                    'video_name' => $first->video->name ?? 'N/A',
                    'phone' => $first->phone,
                    'email' => $first->email,
                    'total_queries' => $group->count(),
                ];
            })
            ->values(); // reset keys

        if (request()->ajax()) {
            return response()->json([
                'data' => $videoQueries
            ]);
        }

        return view('website.studentquery.show', compact('student', 'videoQueries'));
    }



    public function editquery($id)
{
    // Fetch the specific query
    $videoQuery = StudentQuery::findOrFail($id);

    // Fetch all queries by the same student (assuming you want to show multiple queries)
    $allQueries = StudentQuery::where('student_id', $videoQuery->student_id)->get();

    // Fetch video list for dropdown
    $subjectvideo = SubjectVideo::pluck('Name', 'id')->toArray();

    // Fetch student list for dropdown
    $student = Students::pluck('name', 'id')->toArray();

    return view('website.studentquery.reslove', compact('videoQuery', 'allQueries', 'subjectvideo', 'student'));
}





public function updateAnswer(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'answer' => 'required|string|min:5',
        'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ], 422);
    }

    try {
        $studentQuery = StudentQuery::findOrFail($id);

        // Decode old attachments (if any)
        $attachmentPath = json_decode($studentQuery->attachment, true) ?? ['answer' => []];

        // Append new files to 'answer' array
        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $filePath = uploadFile($file, 'attachments'); // You must define uploadFile() helper
                $attachmentPath['answer'][] = $filePath;
            }
        }

        // Update only answer and attachment fields
        $studentQuery->update([
            'answer' => $request->input('answer'),
            'attachment' => !empty($attachmentPath['answer']) ? json_encode($attachmentPath, JSON_FORCE_OBJECT) : null,
            'status' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Answer submitted successfully!',
            'data' => $studentQuery
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to submit answer: ' . $e->getMessage()
        ], 500);
    }
}




   



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($queryID)
    {
        $studentquery = StudentQuery::findOrFail($queryID);
        $student = Students::pluck('name', 'id');
        $subjectvideo = SubjectVideo::pluck('name', 'id');
        $allQueries = StudentQuery::where('student_id', $studentquery->student_id)->get();

        return view('website.studentquery.edit', compact('studentquery', 'student', 'subjectvideo', 'allQueries'));
    }




    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'video_id' => 'required|exists:subject_videos,id',
    //         'student_id' => 'required|exists:students,id',
    //         'email' => 'required|email|max:255',
    //         'phone' => 'required|digits:10',
    //         'query' => 'required|string|min:10',
    //         'answer' => 'nullable|string',
    //         'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     try {
    //         $studentQuery = StudentQuery::findOrFail($id);

    //         $attachmentPath = json_decode($studentQuery->attachment, true) ?? ['answer' => []];

    //         if ($request->hasFile('attachment')) {
    //             foreach ($request->file('attachment') as $file) {
    //                 $filePath = uploadFile($file, 'attachments');
    //                 $attachmentPath['answer'][] = $filePath;
    //             }
    //         }

    //         $studentQuery->update([
    //             'video_id' => $request->input('video_id'),
    //             'student_id' => $request->input('student_id'),
    //             'email' => $request->input('email'),
    //             'phone' => $request->input('phone'),
    //             'query' => $request->input('query'),
    //             'answer' => $request->input('answer', null),
    //             'attachment' => !empty($attachmentPath['answer']) ? json_encode($attachmentPath, JSON_FORCE_OBJECT) : null,
    //             'status' => 1,
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Student query updated successfully!',
    //             'data' => $studentQuery
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Failed to update student query: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($studentqueryId)
    { {
            try {
                $studentquery = StudentQuery::destroy($studentqueryId);
                return ['status' => 'success', 'message' => 'Student deleted successfully!'];
            } catch (\Throwable $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
    public function status($id)
    {
        try {
            $studentquery = StudentQuery::findOrFail($id);
            if ($studentquery) {
                $studentquery->status = $studentquery->status == 1 ? 0 : 1;
                $studentquery->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $studentquery->name . ' Status updated successfully!',
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

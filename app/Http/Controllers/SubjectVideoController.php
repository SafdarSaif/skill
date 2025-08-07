<?php

namespace App\Http\Controllers;

use App\Models\SubjectVideo;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Subject;
use App\Models\CourseType as Type;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;


class SubjectVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function index(Request $request)
    // {
    //     $id = $request->query('id');

    //     if ($request->ajax()) {
    //         $query = SubjectVideo::with(['subject', 'user'])->orderBy('id', 'desc');

    //         if ($id) {
    //             $query->where('subject_id', $id);
    //         }

    //         $data = $query->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->editColumn('created_at', function ($data) {
    //                 return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y h:i A') : 'N/A';
    //             })
    //             ->addColumn('subject_name', function ($data) {
    //                 return $data->subject ? $data->subject->name : 'N/A';
    //             })
    //             ->addColumn('user_name', function ($data) {
    //                 return $data->user ? $data->user->name : 'N/A';
    //             })
    //             ->make(true);
    //     }

    //     return view('subject.video.index');
    // }

    // User based access control
    public function index(Request $request)
    {
        $id = $request->query('id');

        if ($request->ajax()) {
            if (Auth::check() && Auth::user()->hasRole('Super Admin')) {
                $query = SubjectVideo::with(['subject', 'user'])
                    ->when($id, function ($q) use ($id) {
                        return $q->where('subject_id', $id);
                    })
                    ->orderBy('id', 'desc');
            } else {
                $userId = Auth::id();
                $query = SubjectVideo::with(['subject', 'user'])
                    ->where('user_id', $userId)
                    ->when($id, function ($q) use ($id) {
                        return $q->where('subject_id', $id);
                    })
                    ->orderBy('id', 'desc');
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('subject_name', function ($data) {
                    return $data->subject ? $data->subject->name : 'N/A';
                })
                ->addColumn('user_name', function ($data) {
                    return $data->user ? $data->user->name : 'N/A';
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y h:i A') : 'N/A';
                })
                ->make(true);
        }

        return view('subject.video.index');
    }


    /**
     * Show the form for creating a new resource.
     */

    public function getCategories(Request $request)
    {
        $categories = Category::whereHas('courses', function ($query) use ($request) {
            $query->where('type_id', $request->type_id);
        })->pluck('name', 'id');

        return response()->json($categories);
    }

    public function getCourses(Request $request)
    {
        $courses = Course::where('type_id', $request->type_id)
            ->where('category_id', $request->category_id)
            ->pluck('name', 'id');

        return response()->json($courses);
    }

    public function getSubjects(Request $request)
    {
        $subjects = Subject::where('course_id', $request->course_id)
            ->pluck('name', 'id');

        return response()->json($subjects);
    }

    public function create()
    {
        $types = Type::where('status', 1)->pluck('name', 'id');
        $categories = Category::where('status', 1)->pluck('name', 'id');
        $courses = Course::where('status', 1)->pluck('name', 'id');
        $subjects = Subject::where('status', 1)->pluck('name', 'id');
        $users = User::where('status', 1)->pluck('name', 'id');

        return view('subject.video.create', compact('types', 'categories', 'courses', 'subjects', 'users'));
    }



    public function getDuration(Request $request)
    {
        $url = $request->input('drive_url');

        // Extract file ID from Google Drive URL
        preg_match('/\/d\/(.*?)\//', $url, $matches);
        $fileId = $matches[1] ?? null;

        if (!$fileId) {
            return response()->json(['error' => 'Invalid Google Drive URL'], 400);
        }

        // Download URL
        $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
        $tempPath = storage_path('app/temp_video.mp4');

        // Download video
        $fp = fopen($tempPath, 'w+');
        $ch = curl_init($downloadUrl);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        // Load ffmpeg instance
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($tempPath);

        // Get duration in seconds
        $format = $video->getFormat();
        $duration = $format->get('duration'); // in seconds

        // Clean up temp file
        unlink($tempPath);

        if ($duration) {
            return response()->json(['duration' => gmdate("H:i:s", (int)$duration)]);
        }

        return response()->json(['error' => 'Unable to get video duration'], 500);
    }


    /**
     * Store a newly created resource in storage.
     */


    // public function store(Request $request)
    // {
    //     // Validate the request
    //     $validator = Validator::make($request->all(), [
    //         'type_id'     => 'required|exists:course_types,id',
    //         'category_id' => 'required|exists:categories,id',
    //         'course_id'   => 'required|exists:courses,id',
    //         'subject_id'  => 'required|exists:subjects,id',
    //         // 'subject_id'  => 'required|exists:subjects,id',
    //         'name'        => 'required|string|min:3|max:255|unique:subject_videos,name',
    //         'description' => 'nullable|string|max:500',
    //         'duration'    => 'nullable|regex:/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/',
    //         // 'user_id'     => 'required|exists:users,id',
    //         'position'    => 'required|integer|in:0,1',
    //         'upload_type' => 'required|in:youtube,local',
    //         // 'video_url'   => 'nullable|required_if:upload_type,youtube|url|regex:/^https?:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+$/',
    //         'video_url'   => 'nullable|required_if:upload_type,youtube|url',
    //         'video_file'  => 'nullable|required_if:upload_type,local',
    //         // 'video_file' => 'nullable|required_if:upload_type,local|max:1048576',

    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }

    //     try {
    //         $videoUrl = null;

    //         if ($request->upload_type === 'youtube') {
    //             $videoUrl = $request->video_url;
    //         } elseif ($request->hasFile('video_file')) {
    //             $videoUrl = uploadFile($request->file('video_file'), 'subject_videos');
    //         }

    //         // Convert HH:MM:SS to seconds
    //         $durationInSeconds = null;
    //         if ($request->filled('duration')) {
    //             preg_match('/^(\d{2}):(\d{2}):(\d{2})$/', $request->duration, $matches);
    //             if ($matches) {
    //                 $hours   = (int) $matches[1];
    //                 $minutes = (int) $matches[2];
    //                 $seconds = (int) $matches[3];

    //                 $durationInSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
    //             }
    //         }
    //         $subjectVideo = SubjectVideo::create([
    //             'type_id'      => $request->type_id,
    //             'category_id'  => $request->category_id,
    //             'course_id'    => $request->course_id,
    //             'subject_id'  => $request->subject_id,
    //             'name'        => $request->name,
    //             'description' => $request->description,
    //             // 'duration'    => $request->duration,
    //             'duration'    => $durationInSeconds, // Store in seconds
    //             // 'user_id'     => $request->user_id,
    //             'user_id' => Auth::user()->id,
    //             'position'    => $request->position,
    //             'upload_type' => $request->upload_type,
    //             'video_url'   => $videoUrl,
    //         ]);

    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'Subject video added successfully!',
    //             'data'    => $subjectVideo
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Something went wrong: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    // Video Upload with google drive link
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'type_id'     => 'required|exists:course_types,id',
            'category_id' => 'required|exists:categories,id',
            'course_id'   => 'required|exists:courses,id',
            'subject_id'  => 'required|exists:subjects,id',
            // 'subject_id'  => 'required|exists:subjects,id',
            'name'        => 'required|string|min:3|max:255|unique:subject_videos,name',
            'description' => 'nullable|string|max:500',
            'duration'    => 'nullable|regex:/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/',
            'user_id'     => 'required|exists:users,id',
            'position'    => 'required|integer|in:0,1',
            'upload_type' => 'required|in:youtube,local,drive_link',
            // 'video_url'   => 'nullable|required_if:upload_type,youtube|url|regex:/^https?:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+$/',
            // 'video_url'   => 'nullable|required_if:upload_type,youtube|url',
             'video_url'   => [
                'nullable',
                'required_if:upload_type,youtube',
                'url',
                'regex:/^https:\/\/(www\.)?youtube\.com\/embed\/[a-zA-Z0-9_-]+(\?.*)?$/'
            ],
            'video_file'  => 'nullable|required_if:upload_type,local',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $videoUrl = null;

            if ($request->upload_type === 'youtube') {
                $videoUrl = $request->video_url;
            } elseif ($request->hasFile('video_file')) {
                $videoUrl = uploadFile($request->file('video_file'), 'subject_videos');
            } elseif ($request->upload_type === 'drive_link') {
                $videoUrl = $request->drive_link;
                preg_match('/\/d\/(.*?)\//', $videoUrl, $matches);
                if (!isset($matches[1])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid Google Drive URL.'
                    ], 400);
                }
                $fileId = $matches[1];

                // Create streamable link
                $videoUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
            }
            // dd($videoUrl);
            // Convert HH:MM:SS to seconds
            $durationInSeconds = null;
            if ($request->filled('duration')) {
                preg_match('/^(\d{2}):(\d{2}):(\d{2})$/', $request->duration, $matches);
                if ($matches) {
                    $hours   = (int) $matches[1];
                    $minutes = (int) $matches[2];
                    $seconds = (int) $matches[3];

                    $durationInSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
                }
            }
            $subjectVideo = SubjectVideo::create([
                'type_id'      => $request->type_id,
                'category_id'  => $request->category_id,
                'course_id'    => $request->course_id,
                'subject_id'  => $request->subject_id,
                'name'        => $request->name,
                'description' => $request->description,
                // 'duration'    => $request->duration,
                'duration'    => $durationInSeconds, // Store in seconds
                'user_id'     => $request->user_id,
                'position'    => $request->position,
                'upload_type' => $request->upload_type,
                'video_url'   => $videoUrl,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Subject video added successfully!',
                'data'    => $subjectVideo
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
    public function show($id)
    {
        $video = SubjectVideo::findOrFail($id);

        return response()->json([
            'video_url'   => $video->video_url,
            'upload_type' => $video->upload_type,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($subjectvideoId)
    {
        $video = SubjectVideo::findOrFail($subjectvideoId);
        $types = Type::where('status', 1)->pluck('name', 'id');
        $categories = Category::where('status', 1)->pluck('name', 'id');
        $courses = Course::where('status', 1)->pluck('name', 'id');
        $subjects = Subject::where('status', 1)->pluck('name', 'id');
        $users = User::where('status', 1)->pluck('name', 'id');
        return view('subject.video.edit', compact('video', 'subjects', 'users', 'types', 'categories', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $subjectvideoId)
    // {
    //     $subjectVideo = SubjectVideo::findOrFail($subjectvideoId);

    //     $validator = Validator::make($request->all(), [
    //         'type_id'     => 'required|exists:course_types,id',
    //         'category_id' => 'required|exists:categories,id',
    //         'course_id'   => 'required|exists:courses,id',
    //         'subject_id'  => 'required|exists:subjects,id',
    //         'name'        => 'required|string|min:3|max:255|unique:subject_videos,name,' . $subjectvideoId,
    //         'description' => 'nullable|string|max:500',
    //         'duration'    => 'nullable|regex:/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/',
    //         'user_id'     => 'required|exists:users,id',
    //         'position'    => 'required|integer|in:0,1',
    //         'upload_type' => 'required|in:youtube,local',
    //         // 'video_url'   => 'nullable|required_if:upload_type,youtube|url|regex:/^https?:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+$/',
    //         'video_url'   => 'nullable|required_if:upload_type,youtube',
    //         'video_file'  => 'nullable|mimes:mp4,avi,mkv,mov',

    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }

    //     try {
    //         $videoUrl = $subjectVideo->video_url;

    //         if ($request->upload_type === 'youtube') {
    //             $videoUrl = $request->video_url;
    //         } elseif ($request->hasFile('video_file')) {
    //             if ($subjectVideo->upload_type === 'local' && $subjectVideo->video_url) {
    //                 deleteFile($subjectVideo->video_url);
    //             }
    //             $videoUrl = uploadFile($request->file('video_file'), 'subject_videos');
    //         }

    //         // Convert HH:MM:SS to seconds
    //         $durationInSeconds = null;
    //         if ($request->filled('duration')) {
    //             preg_match('/^(\d{2}):(\d{2}):(\d{2})$/', $request->duration, $matches);
    //             if ($matches) {
    //                 $hours   = (int) $matches[1];
    //                 $minutes = (int) $matches[2];
    //                 $seconds = (int) $matches[3];

    //                 $durationInSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
    //             }
    //         }

    //         // Update the subject video
    //         $subjectVideo->update([
    //             'type_id'      => $request->type_id,
    //             'category_id'  => $request->category_id,
    //             'course_id'    => $request->course_id,
    //             'subject_id'  => $request->subject_id,
    //             'name'        => $request->name,
    //             'description' => $request->description,
    //             // 'duration'    => $request->duration,
    //             'duration'    => $durationInSeconds, // Store in seconds
    //             'user_id'     => $request->user_id,
    //             'position'    => $request->position,
    //             'upload_type' => $request->upload_type,
    //             'video_url'   => $videoUrl,
    //         ]);

    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'Subject video updated successfully!',
    //             'data'    => $subjectVideo
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Something went wrong: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    // Video Upload with google drive link
    public function update(Request $request, $subjectvideoId)
    {
        $subjectVideo = SubjectVideo::findOrFail($subjectvideoId);

        $validator = Validator::make($request->all(), [
            'type_id'     => 'required|exists:course_types,id',
            'category_id' => 'required|exists:categories,id',
            'course_id'   => 'required|exists:courses,id',
            'subject_id'  => 'required|exists:subjects,id',
            'name'        => 'required|string|min:3|max:255|unique:subject_videos,name,' . $subjectvideoId,
            'description' => 'nullable|string|max:500',
            'duration'    => 'nullable|regex:/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/',
            'user_id'     => 'required|exists:users,id',
            'position'    => 'required|integer|in:0,1',
            'upload_type' => 'required|in:youtube,local,drive_link',
            // 'video_url'   => 'nullable|required_if:upload_type,youtube|url|regex:/^https?:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+$/',
            // 'video_url'   => 'nullable|required_if:upload_type,youtube',
            'video_url'   => [
                'nullable',
                'required_if:upload_type,youtube',
                'url',
                'regex:/^https:\/\/(www\.)?youtube\.com\/embed\/[a-zA-Z0-9_-]+(\?.*)?$/'
            ],

            'video_file'  => 'nullable|mimes:mp4,avi,mkv,mov',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $videoUrl = $subjectVideo->video_url;

            if ($request->upload_type === 'youtube') {
                $videoUrl = $request->video_url;
            } elseif ($request->hasFile('video_file')) {
                if ($subjectVideo->upload_type === 'local' && $subjectVideo->video_url) {
                    deleteFile($subjectVideo->video_url);
                }
                $videoUrl = uploadFile($request->file('video_file'), 'subject_videos');
            } elseif ($request->upload_type === 'drive_link') {
                $videoUrl = $request->drive_link;
                preg_match('/\/d\/(.*?)\//', $videoUrl, $matches);
                if ($videoUrl != $subjectVideo->video_url) {
                    if (!isset($matches[1])) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Invalid Google Drive URL.'
                        ], 400);
                    }
                    $fileId = $matches[1];
                    // Create streamable link
                    $videoUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
                }
            }

            // Convert HH:MM:SS to seconds
            $durationInSeconds = null;
            if ($request->filled('duration')) {
                preg_match('/^(\d{2}):(\d{2}):(\d{2})$/', $request->duration, $matches);
                if ($matches) {
                    $hours   = (int) $matches[1];
                    $minutes = (int) $matches[2];
                    $seconds = (int) $matches[3];

                    $durationInSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
                }
            }

            // Update the subject video
            $subjectVideo->update([
                'type_id'      => $request->type_id,
                'category_id'  => $request->category_id,
                'course_id'    => $request->course_id,
                'subject_id'  => $request->subject_id,
                'name'        => $request->name,
                'description' => $request->description,
                // 'duration'    => $request->duration,
                'duration'    => $durationInSeconds, // Store in seconds
                'user_id'     => $request->user_id,
                'position'    => $request->position,
                'upload_type' => $request->upload_type,
                'video_url'   => $videoUrl,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Subject video updated successfully!',
                'data'    => $subjectVideo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subjectvideoId)
    {

        // try {
        //     $subjectVideo = SubjectVideo::destroy($subjectvideoId);
        //     return ['status' => 'success', 'message' => 'Subject Video deleted successfully!'];
        // } catch (\Throwable $e) {
        //     return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        // }

        try {
            $data = SubjectVideo::findOrFail($subjectvideoId);
            if ($data) {
                SubjectVideo::find($subjectvideoId)->delete();
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
}

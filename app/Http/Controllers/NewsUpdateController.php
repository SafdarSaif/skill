<?php

namespace App\Http\Controllers;

use App\Models\NewsUpdate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\NewsRead;
use App\Models\Students;
use Illuminate\Support\Str;


class NewsUpdateController extends Controller
{

    /**
     * Get all News for API request
     */


    // public function getNew()
    // {
    //     try {
    //         // $news = NewsUpdate::where('status', 1)->get()->toArray();
    //         $news = NewsUpdate::where('status', 1)
    //             ->orderBy('created_at', 'desc')
    //             ->get()
    //             ->toArray();
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $news
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong! ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    // API with  pagination and limit
    // public function getNew(Request $request)
    // {
    //     try {
    //         $perPage = $request->get('limit', 10); 

    //         $news = NewsUpdate::where('status', 1)
    //             ->orderBy('created_at', 'desc')
    //             ->paginate($perPage);

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $news
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong! ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    // public function getNew(Request $request)
    // {
    //     try {
    //         $studentId = $request->header('student_id');

    //         if (!$studentId) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'student_id is required in headers'
    //             ], 422);
    //         }

    //         $readIds = NewsRead::where('student_id', $studentId)
    //             ->pluck('news_update_id')
    //             ->toArray();

    //         $readNews = NewsUpdate::whereIn('id', $readIds)
    //             ->where('status', 1)
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //         $unreadNews = NewsUpdate::whereNotIn('id', $readIds)
    //             ->where('status', 1)
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => [
    //                 'read' => $readNews,
    //                 'unread' => $unreadNews
    //             ]
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong! ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    public function getNew(Request $request)
{
    try {
        $studentId = $request->header('student_id');
        $limit = (int) $request->header('limit', 10); // Default limit = 10
        $page = (int) $request->header('page', 1);    // Default page = 1

        // Base query for active news
        $query = NewsUpdate::where('status', 1)->orderBy('created_at', 'desc');

        // Total counts before pagination
        $totalCount = $query->count();

        $readIds = [];
        $readCount = 0;
        $unreadCount = $totalCount;

        // If student ID is provided, calculate read/unread
        if ($studentId) {
            $readIds = NewsRead::where('student_id', $studentId)
                ->pluck('news_update_id')
                ->toArray();

            $readCount = NewsUpdate::whereIn('id', $readIds)->where('status', 1)->count();
            $unreadCount = $totalCount - $readCount;
        }

        // Apply pagination
        $newsPaginated = $query->skip(($page - 1) * $limit)->take($limit)->get();

        // Mark read/unread status only if student ID exists
        $newsWithReadStatus = $newsPaginated->map(function ($news) use ($readIds, $studentId) {
            $news->read = $studentId ? in_array($news->id, $readIds) : null;
            return $news;
        });

        return response()->json([
            'status' => true,
            'data' => [
                'content' => $newsWithReadStatus,
                'count' => [
                    'total' => $totalCount,
                    'read' => $readCount,
                    'unread' => $unreadCount
                ],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total_pages' => ceil($totalCount / $limit)
                ]
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong!',
            'error' => $e->getMessage()
        ], 500);
    }
}





    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = NewsUpdate::orderBy('id', 'desc')->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->editColumn('created_at', function ($data) {
    //                 return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
    //             })
    //             ->make(true);
    //     }
    //     return view('website.news.index');
    // }

    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->hasPermissionTo('view news')) {
        if ($request->ajax()) {
            $data = NewsUpdate::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('content', function ($data) {
                    $plainText = strip_tags($data->content); 
                    $shortText = Str::limit($plainText, 100); 
                    return $shortText . (strlen($plainText) > 100 ? '...' : ''); 
                })
                
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('website.news.index');
        } else {
            return response()->view('errors.403', [], 403);
        }
    }


    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->hasPermissionTo('create news')) 
        {

            return view('website.news.create');
        }
         else {
            return response()->view('errors.403', [], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|min:3|max:255|unique:news_updates,name',
            'content' => 'required|string|min:10',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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
                $imagePath = uploadImage($request->file('image'), 'news_images');
            }

            $news = NewsUpdate::create([
                'name'   => $request->title,
                'content' => $request->content,
                'image'   => $imagePath,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'News added successfully!',
                'data'    => $news
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(NewsUpdate $newsUpdate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($newID)
    {
        $news = NewsUpdate::findOrFail($newID);


        return view('website.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|min:3|max:255|unique:news_updates,name,' . $id,
            'content' => 'required|string|min:10',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $news = NewsUpdate::findOrFail($id);
            $imagePath = $news->image;

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($news->image) {
                    deleteImage($news->image);
                }
                // Upload new image
                $imagePath = uploadImage($request->file('image'), 'news_images');
            }

            $news->update([
                'name'   => $request->title,
                'content' => $request->content,
                'image'   => $imagePath,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'News updated successfully!',
                'data'    => $news
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
    // public function destroy($newID)
    // { {
    //         try {
    //             $news = NewsUpdate::destroy($newID);
    //             return ['status' => 'success', 'message' => 'News  deleted successfully!'];
    //         } catch (\Throwable $e) {
    //             return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //         }
    //     }
    // }
    public function destroy($id)
    {
        try {
            $data = NewsUpdate::findOrFail($id);
            if ($data) {
                NewsUpdate::find($id)->delete();
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
    // public function destroy($newID)
    // {
    //     if (!Auth::user()->hasPermissionTo('delete news')) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Unauthorized access'
    //         ], 403);
    //     }

    //     try {
    //         $news = NewsUpdate::findOrFail($newID);

    //         if ($news->image) {
    //             deleteImage($news->image);
    //         }

    //         $news->delete();

    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'News deleted successfully!'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Something went wrong! ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function status($id)
    {
        try {
            $news = NewsUpdate::findOrFail($id);
            if ($news) {
                $news->status = $news->status == 1 ? 0 : 1;
                $news->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $news->name . ' status updated successfully!',
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

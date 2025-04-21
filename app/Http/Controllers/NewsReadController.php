<?php

namespace App\Http\Controllers;

use App\Models\NewsRead;
use Illuminate\Http\Request;

class NewsReadController extends Controller
{


    /**
     * Mark a news update as read by a student.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    // public function markAsRead(Request $request)
    // {
    //     $request->validate([
    //         'student_id' => 'required|exists:students,id',
    //         'news_update_id' => 'required|exists:news_updates,id',
    //     ]);

    //     $newsRead = NewsRead::firstOrCreate(
    //         [
    //             'student_id' => $request->student_id,
    //             'news_update_id' => $request->news_update_id,
    //         ],
    //         [
    //             'read_at' => now(),
    //         ]
    //     );

    //     return response()->json(['message' => 'News marked as read', 'data' => $newsRead]);
    // }
    public function markAsRead(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'student_id' => 'required|exists:students,id',
                'news_update_id' => 'required|exists:news_updates,id',
            ], [
                'student_id.required' => 'Student ID is required.',
                'student_id.exists' => 'Student not found.',
                'news_update_id.required' => 'News ID is required.',
                'news_update_id.exists' => 'News not found.',
            ]);
    
            $studentId = $request->student_id;
            $newsId = $request->news_update_id;
    
            // Check if already marked
            $alreadyRead = NewsRead::where('student_id', $studentId)
                ->where('news_update_id', $newsId)
                ->exists();
    
            // Mark as read or update timestamp
            $newsRead = NewsRead::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'news_update_id' => $newsId,
                ],
                [
                    'read_at' => now(),
                ]
            );
    
            return response()->json([
                'status' => true,
                'message' => $alreadyRead 
                    ? 'Student had already marked this news as read.' 
                    : 'Student has marked the news as read.',
                'data' => [
                    'student_id' => $newsRead->student_id,
                    'news_update_id' => $newsRead->news_update_id,
                    'read_at' => $newsRead->read_at,
                    'already_read' => $alreadyRead
                ]
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsRead $newsRead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsRead $newsRead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsRead $newsRead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsRead $newsRead)
    {
        //
    }
}

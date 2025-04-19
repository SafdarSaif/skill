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

    public function markAsRead(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'news_update_id' => 'required|exists:news_updates,id',
        ]);

        $newsRead = NewsRead::firstOrCreate(
            [
                'student_id' => $request->student_id,
                'news_update_id' => $request->news_update_id,
            ],
            [
                'read_at' => now(),
            ]
        );

        return response()->json(['message' => 'News marked as read', 'data' => $newsRead]);
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

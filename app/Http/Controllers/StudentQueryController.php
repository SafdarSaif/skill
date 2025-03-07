<?php

namespace App\Http\Controllers;

use App\Models\StudentQuery;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class StudentQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentQuery::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
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
        return view('website.studentquery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|min:3|max:255',
            'email'        => 'required|email|unique:student_queries,email',
            'phone'        => 'required|digits:10',
            'query'        => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // Create student query
            $studentQuery = StudentQuery::create([
                'name'  => $request->input('student_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'query' => $request->input('query'), // Corrected to avoid conflict
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Student query submitted successfully!',
                'data'    => $studentQuery,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(StudentQuery $studentQuery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($queryID)
    {
        $studentquery = StudentQuery::findOrFail($queryID);


        return view('website.studentquery.edit', compact('studentquery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentQuery $studentQuery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentQuery $studentQuery)
    {
        //
    }
}

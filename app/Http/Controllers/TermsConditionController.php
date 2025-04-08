<?php

namespace App\Http\Controllers;

use App\Models\TermsCondition;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TermsConditionController extends Controller
{

    /**
     * Get all Privacy for API request
     */
    public function getTerms()
    {
        try {
            $term = TermsCondition::where('status', 1)->get()->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $term
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TermsCondition::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('term.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('term.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $term = TermsCondition::create([
                'content' => $request->content,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Terms & Conditions added successfully!',
                'data'    => $term,
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
    public function show(TermsCondition $termsCondition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($termID)
    {
        $term = TermsCondition::findOrFail($termID);


        return view('term.edit', compact('term'));
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
            'content' => 'required|string|min:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $term = TermsCondition::findOrFail($id);
            $term->update([
                'content' => $request->content,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Terms & Conditions updated successfully!',
                'data'    => $term,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($termID)
    // { {
    //         try {
    //             $term = TermsCondition::destroy($termID);
    //             return ['status' => 'success', 'message' => 'Faq  deleted successfully!'];
    //         } catch (\Throwable $e) {
    //             return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //         }
    //     }
    // }

    public function destroy($id)
    {
        try {
            $data = TermsCondition::findOrFail($id);
            if ($data) { 
                TermsCondition::find($id)->delete();
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
            $term = TermsCondition::findOrFail($id);
            if ($term) {
                $term->status = $term->status == 1 ? 0 : 1;
                $term->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $term->name . ' status updated successfully!',
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

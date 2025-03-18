<?php

namespace App\Http\Controllers;

use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class PrivacyPolicyController extends Controller
{


      /**
     * Get all Privacy for API request
     */
    public function getPrivacy()
    {
        try {
            // $privacy = PrivacyPolicy::where('status', 1)->get()->toArray();
            $privacy = PrivacyPolicy::all()->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $privacy
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
            $data = PrivacyPolicy::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('privacy.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('privacy.create');
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
            $privacy = PrivacyPolicy::create([
                'content' => $request->content,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'privacys & Conditions added successfully!',
                'data'    => $privacy,
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
    public function show(PrivacyPolicy $privacyPolicy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($privacyID)
    {
        $privacy = PrivacyPolicy::findOrFail($privacyID);


        return view('privacy.edit', compact('privacy'));
    }

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
            $privacy = PrivacyPolicy::findOrFail($id);
            $privacy->update([
                'content' => $request->content,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'privacys & Conditions updated successfully!',
                'data'    => $privacy,
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
    public function destroy($privacyID)
    { {
            try {
                $privacy = PrivacyPolicy::destroy($privacyID);
                return ['status' => 'success', 'message' => 'Faq  deleted successfully!'];
            } catch (\Throwable $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function status($id)
    {
        try {
            $privacy = PrivacyPolicy::findOrFail($id);
            if ($privacy) {
                $privacy->status = $privacy->status == 1 ? 0 : 1;
                $privacy->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $privacy->name . ' status updated successfully!',
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

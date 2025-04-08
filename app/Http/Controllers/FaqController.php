<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;


class FaqController extends Controller
{

    /**
     * Get all FAQs for API request
     */
    // public function getFaqs()
    // {
    //     try {
    //         $faqs = Faq::where('status', 1)->get()->toArray();
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $faqs
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong! ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    


// API for FAQ with pagination and limit
public function getFaqs(Request $request)
{
    try {
        $perPage = $request->get('limit', 1); // Default: 10 FAQs per page

        $faqs = Faq::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $faqs
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
            $data = Faq::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('website.faq.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:5|max:255|unique:faqs,question',
            'answer'   => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $faq = Faq::create([
                'question' => $request->question,
                'answer'   => $request->answer,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'FAQ added successfully!',
                'data'    => $faq,
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
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($faqID)
    {
        $faq = Faq::findOrFail($faqID);


        return view('website.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified FAQ in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:5|max:255|unique:faqs,question,' . $id,
            'answer'   => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $faq = Faq::findOrFail($id);

            $faq->update([
                'question' => $request->question,
                'answer'   => $request->answer,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'FAQ updated successfully!',
                'data'    => $faq
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
    public function destroy($faqID)
    { {
            try {
                $faqs = Faq::destroy($faqID);
                return ['status' => 'success', 'message' => 'Faq  deleted successfully!'];
            } catch (\Throwable $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function status($id)
    {
        try {
            $news = Faq::findOrFail($id);
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

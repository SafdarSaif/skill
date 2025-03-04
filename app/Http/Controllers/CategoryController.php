<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('coursemangement.categorycourse.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coursemangement.categorycourse.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|min:2|max:255',
            'slug' => 'nullable|alpha_dash|unique:categories,slug',
            'description' => 'nullable|max:500',
        ]);

        try {
            // Determine the slug
            $slug = $request->slug;
            if (!$slug) {
                $slug = Str::slug($request->name);
            }

            // Create a new category
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $slug;
            $category->description = $request->description;
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category added successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        return view('coursemangement.categorycourse.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $categoryId)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|min:2|max:255',
            'slug' => 'nullable|alpha_dash|unique:categories,slug,' . $categoryId,
            'description' => 'nullable|max:500',
        ]);
    
        try {
            // Find the category by ID
            $category = Category::findOrFail($categoryId);
    
            // Determine the slug
            $slug = $request->slug;
            if (!$slug) {
                $slug = Str::slug($request->name);
            }
    
            // Update category fields
            $category->name = $request->name;
            $category->slug = $slug;
            $category->description = $request->description;
            $category->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categoryId)
    { {
            try {
                $category = Category::destroy($categoryId);
                return ['status' => 'success', 'message' => 'Course Category  deleted successfully!'];
            } catch (\Throwable $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function status($id)
    {
        try {
            $category = Category::findOrFail($id);
            if ($category) {
                $category->status = $category->status == 1 ? 0 : 1;
                $category->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $category->name . ' status updated successfully!',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found',
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

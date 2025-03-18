<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


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
    // public function store(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'name' => 'required|min:2|max:255',
    //         'slug' => 'nullable|alpha_dash|unique:categories,slug',
    //         'description' => 'nullable|max:500',
    //     ]);

    //     try {
    //         // Determine the slug
    //         $slug = $request->slug;
    //         if (!$slug) {
    //             $slug = Str::slug($request->name);
    //         }

    //         // Create a new category
    //         $category = new Category();
    //         $category->name = $request->name;
    //         $category->slug = $slug;
    //         $category->description = $request->description;
    //         $category->save();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Category added successfully!'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'slug' => 'nullable|alpha_dash|unique:categories,slug',
            'description' => 'nullable|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Determine the slug
            $slug = $request->slug;
            if (!$slug) {
                $slug = Str::slug($request->name);
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'category_images');
            }

            // Create a new category
            $category = Category::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $imagePath,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category added successfully!',
                'data' => $category
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
    // public function update(Request $request, $categoryId)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'name' => 'required|min:2|max:255',
    //         'slug' => 'nullable|alpha_dash|unique:categories,slug,' . $categoryId,
    //         'description' => 'nullable|max:500',
    //     ]);

    //     try {
    //         // Find the category by ID
    //         $category = Category::findOrFail($categoryId);

    //         // Determine the slug
    //         $slug = $request->slug;
    //         if (!$slug) {
    //             $slug = Str::slug($request->name);
    //         }

    //         // Update category fields
    //         $category->name = $request->name;
    //         $category->slug = $slug;
    //         $category->description = $request->description;
    //         $category->save();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Category updated successfully!'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function update(Request $request, $categoryId)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'slug' => 'nullable|alpha_dash|unique:categories,slug,' . $categoryId,
            'description' => 'nullable|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Find the category by ID
            $category = Category::findOrFail($categoryId);

            // Determine the slug
            $slug = $request->slug ?: Str::slug($request->name);

            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }

                // Upload new image
                $imagePath = uploadImage($request->file('image'), 'category_images');
                $category->image = $imagePath;
            }

            // Update category fields
            $category->name = $request->name;
            $category->slug = $slug;
            $category->description = $request->description;
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully!',
                'data' => $category
            ], 200);
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function categories(Request $request)
    {
        try {
            $data = Category::all();
            if ($data) {
                return response()->json(['status' => "success", 'message' => "All Category Lists", "data" => $data]);
            } else {
                return response()->json(['status' => "error", 'message' => "No Category Found"]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}

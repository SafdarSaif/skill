<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;

use App\Models\StudentPayment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use App\Http\Controllers\EasebuzzPaymentController;
use App\Models\Students;
use App\Models\CourseType;
use App\Models\StudentCourse;
use Exception;
use Illuminate\Support\Facades\Auth;
use stdClass;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller

{

    /** 
     * Api of all cources
     */



    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check() && Auth::user()->hasRole('Super Admin')) {
                $data = Course::with(['category', 'users', 'type'])->orderBy('id', 'desc')->get();
            } else {
                $userId = Auth::user()->id;
                $data = Course::with(['category'])->whereHas('users', function ($query) use ($userId) {
                    $query->where('id', $userId);
                })->orderBy('id', 'desc')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('category', function ($data) {
                    return $data->category ? $data->category->name : 'N/A';
                })
                ->addColumn('course_type', function ($data) {
                    return $data->type ? $data->type->name : 'N/A';
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
        return view('coursemangement.course.index');
    }


    public function payStuCourseFee(Request $request)
    {

        try {

            $courseArr = Course::find($request->course_id);
            if (!$courseArr) {
                return response()->json(['status' => 'error', 'message' => 'Course not found!']);
            }

            $studentArr = Students::find($request->student_id);
            if (!$studentArr) {
                return response()->json(['status' => 'error', 'message' => 'Student not found!']);
            }

            $transication_id = 'TXN-' . time() . rand(1000, 9999);
            $data = [
                'student_id' => $request->student_id,
                'course_id' => $courseArr->id,
                'amount' => floatval($courseArr->price),
                'payment_status' => "pending",
                'transaction_id' => $transication_id,
                'payment_confirmation_date' => now(),
            ];
            StudentPayment::create($data);

            $paymentdata = [
                'txnid' => trim($transication_id),
                'amount' => floatval($courseArr->price),
                "proinfo" => "Course Payment",
                "name" => trim($studentArr->name),
                "email" => trim($studentArr->email),
                "mobile" => trim($studentArr->mobile)
            ];
            $procced_payment = EasebuzzPaymentController::initiatePayment($paymentdata);

            return response()->json(['status' => 'pending', 'message' => 'Payment is in process', 'data' => $paymentdata, 'api_response' =>  $procced_payment]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during the payment process!',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::where('status', 1)->pluck('name', 'id');
        $courseType = CourseType::where('status', 1)->pluck('name', 'id');

        return view('coursemangement.course.create', compact('category', 'courseType'));
    }

    /**
     * Store a newly created resource in storage.
     */




    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|max:500',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:course_types,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'course_images');
            }

            // Create a new course

            $course = Course::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'duration' => $request->duration,
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,
                'added_by' => Auth::user()->id,
                'image' => $imagePath,
                'status' => 1,
            ]);
            // dd($course);
            return response()->json([
                'status' => 'success',
                'message' => 'Course added successfully!',
                'data' => $course
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($courseID)
    {
        $category = Category::where('status', 1)->pluck('name', 'id');
        $courseType = CourseType::where('status', 1)->pluck('name', 'id');
        $course = Course::findOrFail($courseID);


        return view('coursemangement.course.edit', compact('category', 'course', 'courseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $courseID)
    {
        try {
            // Validate input
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|string|max:100',
                'category_id' => 'required|exists:categories,id',
                'type_id' => 'required|exists:course_types,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $course = Course::findOrFail($courseID);

            if ($request->hasFile('image')) {
                if ($course->image && file_exists(public_path('uploads/course_images/' . $course->image))) {
                    unlink(public_path('uploads/course_images/' . $course->image));
                }
                // Upload new image
                $imagePath = uploadImage($request->file('image'), 'course_images');
                $course->image = $imagePath;
            }

            // Update other fields
            $course->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'duration' => $request->duration,
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,
                'added_by' => Auth::user()->id,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Course updated successfully!'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($courseID)
    // { {
    //         try {
    //             $category = Category::destroy($courseID);
    //             return ['status' => 'success', 'message' => 'Course Category  deleted successfully!'];
    //         } catch (\Throwable $e) {
    //             return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //         }
    //     }
    // }

    public function destroy($id)
    {

        try {
            $data = Course::findOrFail($id);
            if ($data) {
                Course::find($id)->delete();
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
            $course = Course::findOrFail($id);
            if ($course) {
                $course->status = $course->status == 1 ? 0 : 1;
                $course->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $course->name . ' status updated successfully!',
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

    public function bannerStatus($id)
    {
        try {
            $course = Course::findOrFail($id);
            if ($course) {
                $course->is_banner = $course->is_banner == 1 ? 0 : 1;
                $course->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $course->name . ' Banner status updated successfully!',
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




    // All course Api 
    // public function coursesFunc(Request $request, $column = '', $value = '')
    // {
    //     try {
    //         $query = Course::with('category', 'users', 'subjects')->where('status', 1);
    //         if (!empty($column) && !empty($value)) {
    //             $query->where($column, $value);
    //         }

    //         $data = $query->get()->toArray();
    //         if ($data) {
    //             return response()->json(['status' => "success", 'message' => "All Course Lists", "data" => $data]);
    //         } else {
    //             return response()->json(['status' => "error", 'message' => "No Course Found"]);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }





    public function getCourseAmount($courseId)
    {
        $courseAmount = Course::where('id', $courseId)->pluck('price')->first();
        return response()->json(['status' => 'success', 'price' => $courseAmount]);
    }


    // API
    // public function getCourseByType($typeId = '')
    // {
    //     try {
    //         $courses = CourseType::where('status', 1)->whereHas('courses', function ($query) {
    //             $query->where('status', true);
    //         });
    //         if ($typeId != '') {
    //             $courses->where('id', $typeId);
    //         }
    //         $courses = $courses->get()->toArray();
    //         return response()->json(['status' => 'success', 'message' => 'All courses', 'data' => $courses]);
    //     } catch (Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //     }
    // }

    // public function getCourseByType(Request $request, $typeId = 0)
    // {
    //     try {
    //         $studentId = $request->header('student_id');
    //         $limit = $request->header('limit', 10);
    //         $page = $request->header('page', 1); 

    //         // Get all course types (or specific one if $typeId != 0)
    //         $courseTypesQuery = CourseType::where('status', 1)
    //             ->whereHas('courses', function ($query) {
    //                 $query->where('status', 1)
    //                     ->where('is_banner', 0); // ✅ Only non-banner courses
    //             });

    //         if ($typeId != 0) {
    //             $courseTypesQuery->where('id', $typeId);
    //         }

    //         $courseTypes = $courseTypesQuery->get();

    //         $enrolledCourseIds = [];
    //         if ($studentId) {
    //             $enrolledCourseIds = StudentCourse::where('student_id', $studentId)
    //                 ->pluck('course_id')
    //                 ->toArray();
    //         }

    //         $result = [];

    //         foreach ($courseTypes as $type) {
    //             $courses = Course::with('category', 'users', 'subjects')
    //                 ->where('status', 1)
    //                 ->where('is_banner', 0) 
    //                 ->where('type_id', $type->id)
    //                 ->paginate($limit, ['*'], 'page', $page);

    //             // Add is_enrolled flag
    //             $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
    //                 $course->is_enrolled = in_array($course->id, $enrolledCourseIds);
    //                 return $course;
    //             });

    //             $result[] = [
    //                 'type_id' => $type->id,
    //                 'type_name' => $type->name,
    //                 'courses' => $courses->items(),
    //                 'pagination' => [
    //                     'total' => $courses->total(),
    //                     'per_page' => $courses->perPage(),
    //                     'current_page' => $courses->currentPage(),
    //                     'last_page' => $courses->lastPage(),
    //                     'from' => $courses->firstItem(),
    //                     'to' => $courses->lastItem(),
    //                     'next_page_url' => $courses->nextPageUrl(),
    //                     'prev_page_url' => $courses->previousPageUrl(),
    //                 ]
    //             ];
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => $typeId == 0 ? 'All Course Types with Courses' : 'Courses by Type',
    //             'data' => $result
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }


    public function getCourseByType(Request $request, $typeId = 0)
    {
        try {
            $studentId = $request->header('student_id');
            $limit = $request->header('limit', 10);
            $page = $request->header('page', 1);
            $onlyHomepage = $request->header('only_home', false); // Optional: Add header flag for homepage filtering

            // Build base query
            $courseTypesQuery = CourseType::where('status', 1)
                ->whereHas('courses', function ($query) {
                    $query->where('status', 1)
                        ->where('is_banner', 0); // ✅ Only non-banner courses
                });

            if ($typeId != 0) {
                $courseTypesQuery->where('id', $typeId);
            }

            if ($onlyHomepage) {
                $courseTypesQuery->where('is_active_on_home', 1);
            }

            $courseTypes = $courseTypesQuery->get();

            $enrolledCourseIds = [];
            if ($studentId) {
                $enrolledCourseIds = StudentCourse::where('student_id', $studentId)
                    ->pluck('course_id')
                    ->toArray();
            }

            $result = [];

            foreach ($courseTypes as $type) {
                $courses = Course::with('category', 'users', 'subjects')
                    ->where('status', 1)
                    ->where('is_banner', 0)
                    ->where('type_id', $type->id)
                    ->paginate($limit, ['*'], 'page', $page);

                // Mark enrolled courses
                $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
                    $course->is_enrolled = in_array($course->id, $enrolledCourseIds);
                    return $course;
                });

                $result[] = [
                    'type_id' => $type->id,
                    'type_name' => $type->name,
                    'is_active_on_home' => $type->is_active_on_home,
                    'courses' => $courses->items(),
                    'pagination' => [
                        'total' => $courses->total(),
                        'per_page' => $courses->perPage(),
                        'current_page' => $courses->currentPage(),
                        'last_page' => $courses->lastPage(),
                        'from' => $courses->firstItem(),
                        'to' => $courses->lastItem(),
                        'next_page_url' => $courses->nextPageUrl(),
                        'prev_page_url' => $courses->previousPageUrl(),
                    ]
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => $typeId == 0 ? 'All Course Types with Courses' : 'Courses by Type',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }







    //    Seprate API for  Banner course APi 
    // public function bannerCoursesFunc(Request $request)
    // {
    //     try {
    //         $limit = $request->input('limit', 10); 

    //         $data = Course::with('category', 'users', 'subjects')
    //             ->where('status', 1)
    //             ->where('is_banner', 1)
    //             ->paginate($limit);

    //         if ($data->count()) {
    //             return response()->json([
    //                 'status' => "success",
    //                 'message' => "Banner Course List",
    //                 'data' => $data->items(),
    //                 'pagination' => [
    //                     'total' => $data->total(),
    //                     'per_page' => $data->perPage(),
    //                     'current_page' => $data->currentPage(),
    //                     'last_page' => $data->lastPage(),
    //                     'from' => $data->firstItem(),
    //                     'to' => $data->lastItem(),
    //                 ]
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => "error",
    //                 'message' => "No Banner Courses Found"
    //             ]);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }


    // public function bannerCoursesFunc(Request $request)
    // {
    //     try {
    //         $limit = $request->header('limit', 10);

    //         // Get student ID from request header
    //         $studentId = $request->header('student_id');
    //         // dd($studentId); 

    //         $courses = Course::with('category', 'users', 'subjects')
    //             ->where('status', 1)
    //             ->where('is_banner', 1)
    //             ->paginate($limit);

    //         $enrolledCourseIds = [];

    //         if ($studentId) {
    //             $enrolledCourseIds = StudentCourse::where('student_id', $studentId)
    //                 ->pluck('course_id')
    //                 ->toArray();
    //         }

    //         // Add is_enrolled flag to each course
    //         $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
    //             $course->is_enrolled = in_array($course->id, $enrolledCourseIds);
    //             return $course;
    //         });

    //         return response()->json([
    //             'status' => "success",
    //             'message' => "Banner Course List",
    //             'data' => $courses->items(),
    //             'pagination' => [
    //                 'total' => $courses->total(),
    //                 'per_page' => $courses->perPage(),
    //                 'current_page' => $courses->currentPage(),
    //                 'last_page' => $courses->lastPage(),
    //                 'from' => $courses->firstItem(),
    //                 'to' => $courses->lastItem(),
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }



    public function coursesFunc(Request $request, $column = '', $value = '')
    {
        try {
            $studentId = $request->header('student_id');
            $limit = $request->header('limit', 10);

            $query = Course::with('category', 'users', 'subjects')->where('status', 1);

            if (!empty($column) && !empty($value)) {
                $query->where($column, $value);
            }

            $courses = $query->paginate($limit);

            $enrolledCourseIds = [];

            if ($studentId) {
                $enrolledCourseIds = StudentCourse::where('student_id', $studentId)
                    ->pluck('course_id')
                    ->toArray();
            }

            // Add is_enrolled flag to each course
            $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
                $course->is_enrolled = in_array($course->id, $enrolledCourseIds);
                return $course;
            });

            if ($courses->count()) {
                return response()->json([
                    'status' => "success",
                    'message' => "All Course Lists",
                    'data' => $courses->items(),
                    'pagination' => [
                        'total' => $courses->total(),
                        'per_page' => $courses->perPage(),
                        'current_page' => $courses->currentPage(),
                        'last_page' => $courses->lastPage(),
                        'from' => $courses->firstItem(),
                        'to' => $courses->lastItem(),
                    ],
                ]);
            } else {
                return response()->json([
                    'status' => "error",
                    'message' => "No Course Found",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function bannerCoursesFunc(Request $request)
    {
        try {
            $limit = $request->header('limit', 10);
            $page = $request->header('page', 1); // Get the page number from headers (default to 1)

            // Get student ID from request header
            $studentId = $request->header('student_id');

            // Fetch paginated courses with the given limit and page
            $courses = Course::with('category', 'users', 'subjects')
                ->where('status', 1)
                ->where('is_banner', 1)
                ->paginate($limit, ['*'], 'page', $page);

            $enrolledCourseIds = [];

            if ($studentId) {
                $enrolledCourseIds = StudentCourse::where('student_id', $studentId)
                    ->pluck('course_id')
                    ->toArray();
            }

            // Add is_enrolled flag to each course
            $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
                $course->is_enrolled = in_array($course->id, $enrolledCourseIds);
                return $course;
            });

            return response()->json([
                'status' => "success",
                'message' => "Banner Course List",
                'data' => $courses->items(),
                'pagination' => [
                    'total' => $courses->total(),
                    'per_page' => $courses->perPage(),
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                    'next_page_url' => $courses->nextPageUrl(),
                    'prev_page_url' => $courses->previousPageUrl(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}

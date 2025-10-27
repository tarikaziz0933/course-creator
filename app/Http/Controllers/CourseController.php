<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Show all courses with related modules and contents
     */
    public function index()
    {
        $courses = Course::with('modules.contents')
            ->latest()
            ->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a new course with modules & contents
     */
    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $course = $this->createCourse($data);

            if (!empty($data['modules'])) {
                $this->storeModulesAndContents($course, $data['modules']);
            }

            DB::commit();

            return redirect()
                ->route('courses.index')
                ->with('success', 'Course created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Course store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->withErrors(['msg' => 'Something went wrong! Please try again.']);
        }
    }

    /**
     * Create base course record
     */
    private function createCourse(array $data): Course
    {
        return Course::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? null,
        ]);
    }

    /**
     * Store all modules and their contents for a course
     */
    private function storeModulesAndContents(Course $course, array $modules): void
    {
        foreach ($modules as $mIndex => $mData) {
            if (empty($mData['title'])) {
                continue;
            }

            $module = $course->modules()->create([
                'title' => $mData['title'],
                'description' => $mData['description'] ?? null,
                'order' => $mIndex,
            ]);

            if (!empty($mData['contents'])) {
                $this->storeContents($module, $mData['contents']);
            }
        }
    }

    /**
     * Store multiple contents under a single module
     */
    private function storeContents($module, array $contents): void
    {
        $contentPayloads = [];

        foreach ($contents as $cIndex => $cData) {
            if (empty($cData['title'])) {
                continue;
            }

            $payload = [
                'type' => $cData['type'] ?? 'text',
                'title' => $cData['title'],
                'body' => $cData['body'] ?? null,
                'url' => $cData['type'] === 'link' ? ($cData['url'] ?? null) : null,
                'order' => $cIndex,
            ];

            $contentPayloads[] = $payload;
        }

        if (count($contentPayloads)) {
            $module->contents()->createMany($contentPayloads);
        }
    }
}

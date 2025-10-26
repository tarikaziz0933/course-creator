<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('modules.contents')->latest()->get();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $course = Course::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'category' => $data['category'] ?? null,
            ]);

            // Feature video upload
            if ($request->hasFile('feature_video')) {
                $video = $request->file('feature_video');
                $filename = Str::uuid() . '.' . $video->getClientOriginalExtension();
                $path = $video->storeAs('feature_videos', $filename, 'public');
                $course->update(['feature_video' => $path]);
            }

            // Save modules + contents
            if (!empty($data['modules'])) {
                foreach ($data['modules'] as $mIndex => $mData) {
                    if (empty($mData['title'])) {
                        continue;
                    }

                    $module = $course->modules()->create([
                        'title' => $mData['title'],
                        'description' => $mData['description'] ?? null,
                        'order' => $mIndex,
                    ]);

                    if (!empty($mData['contents'])) {
                        foreach ($mData['contents'] as $cIndex => $cData) {
                            $payload = [
                                'type' => $cData['type'] ?? 'text',
                                'title' => $cData['title'] ?? null,
                                'body' => $cData['body'] ?? null,
                                'order' => $cIndex,
                            ];

                            $fileInput = "modules.$mIndex.contents.$cIndex.file";
                            if (in_array($cData['type'], ['image', 'video']) && $request->hasFile($fileInput)) {
                                $file = $request->file($fileInput);
                                $fname = Str::uuid() . '.' . $file->getClientOriginalExtension();
                                $folder = $cData['type'] === 'image' ? 'content_images' : 'content_videos';
                                $fpath = $file->storeAs($folder, $fname, 'public');
                                $payload['file_path'] = $fpath;
                            }

                            if ($cData['type'] === 'link') {
                                $payload['url'] = $cData['url'] ?? null;
                            }

                            $module->contents()->create($payload);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('courses.index')->with('success', 'Course created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Course store error: ' . $e->getMessage());
            return back()->withErrors(['msg' => 'Something went wrong!']);
        }
    }
}
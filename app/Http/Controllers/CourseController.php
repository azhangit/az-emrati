<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Institute;
use App\Models\CourseSchedule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $courses = Course::with(['institute', 'schedules'])->orderBy('created_at', 'desc');
        
        if ($request->has('search')) {
            $sort_search = $request->search;
            $courses = $courses->where('course_module', 'like', '%'.$sort_search.'%');
        }
        
        $courses = $courses->paginate(15);
        return view('backend.courses.index', compact('courses', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $institutes = Institute::orderBy('name', 'asc')->get();
        return view('backend.courses.create', compact('institutes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'course_module' => 'required|string|max:255',
            'schedules' => 'required|array|min:1',
            'schedules.*.date' => 'required|date',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
            'schedules.*.course_level' => 'required|string|max:255',
            'schedules.*.price' => 'required|numeric|min:0',
        ]);

        $course = Course::create([
            'institute_id' => $request->institute_id,
            'course_module' => $request->course_module,
            'image' => $request->image,
            'description' => $request->description,
            'price' => 0, // Base price, actual price is per schedule
        ]);

        // Create schedules
        foreach ($request->schedules as $scheduleData) {
            CourseSchedule::create([
                'course_id' => $course->id,
                'date' => $scheduleData['date'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'course_level' => $scheduleData['course_level'],
                'price' => $scheduleData['price'],
                'is_available' => isset($scheduleData['is_available']) ? $scheduleData['is_available'] : true,
            ]);
        }

        flash(translate('Course has been inserted successfully'))->success();
        return redirect()->route('courses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::with('schedules')->findOrFail($id);
        $institutes = Institute::orderBy('name', 'asc')->get();
        return view('backend.courses.edit', compact('course', 'institutes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'course_module' => 'required|string|max:255',
            'schedules' => 'required|array|min:1',
            'schedules.*.date' => 'required|date',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
            'schedules.*.course_level' => 'required|string|max:255',
            'schedules.*.price' => 'required|numeric|min:0',
        ]);

        $course = Course::findOrFail($id);
        $course->update([
            'institute_id' => $request->institute_id,
            'course_module' => $request->course_module,
            'image' => $request->image,
            'description' => $request->description,
        ]);

        // Get existing schedule IDs
        $existingIds = collect($request->schedules)->pluck('id')->filter()->toArray();
        
        // Delete removed schedules
        $course->schedules()->whereNotIn('id', $existingIds)->delete();

        // Update or create schedules
        foreach ($request->schedules as $scheduleData) {
            if (isset($scheduleData['id'])) {
                // Update existing
                CourseSchedule::where('id', $scheduleData['id'])
                    ->update([
                        'date' => $scheduleData['date'],
                        'start_time' => $scheduleData['start_time'],
                        'end_time' => $scheduleData['end_time'],
                        'course_level' => $scheduleData['course_level'],
                        'price' => $scheduleData['price'],
                        'is_available' => isset($scheduleData['is_available']) ? $scheduleData['is_available'] : true,
                    ]);
            } else {
                // Create new
                CourseSchedule::create([
                    'course_id' => $course->id,
                    'date' => $scheduleData['date'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                    'course_level' => $scheduleData['course_level'],
                    'price' => $scheduleData['price'],
                    'is_available' => isset($scheduleData['is_available']) ? $scheduleData['is_available'] : true,
                ]);
            }
        }

        flash(translate('Course has been updated successfully'))->success();
        return redirect()->route('courses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        flash(translate('Course has been deleted successfully'))->success();
        return redirect()->route('courses.index');
    }
}


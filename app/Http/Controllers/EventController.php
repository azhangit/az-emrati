<?php
// app/Http/Controllers/EventController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Location;
use App\Models\Institute;
use App\Models\Course;
use Illuminate\Http\Request;

class EventController extends Controller
{
   public function index()
{
    $events = Event::with('location')->orderBy('date', 'asc')->paginate(20);
    return view('backend.events.index', compact('events'));
}


    public function create()
    {
      $locations = Location::orderBy('name')->get();
    return view('backend.events.create', compact('locations'));
    }

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'trainer' => 'required|string|max:255',
          //  'location' => 'required|string|max:255',
            'date' => 'required|date',
            //'time' => 'required|string|max:100',
        ]);

        Event::create($request->all());
        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $locations = Location::orderBy('name')->get();
       return view('backend.events.edit', compact('event', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'trainer' => 'required|string|max:255',
           // 'location' => 'required|string|max:255',
            'date' => 'required|date',
          //  'time' => 'required|string|max:100',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
public function academy()
{
    // Relation ko eager load karo
    $events = Event::with('location')->get();
    $locations = Location::orderBy('name')->get();
    $institutes = Institute::orderBy('name', 'asc')->get();
    
    // Get all course schedules with their courses and institutes
    $courseSchedules = \App\Models\CourseSchedule::with(['course.institute'])
        ->where('is_available', true)
        ->orderBy('date', 'asc')
        ->orderBy('start_time', 'asc')
        ->get();

    return view('academy', compact('events', 'locations', 'institutes', 'courseSchedules'));
}

public function showCoursesByInstitute($id)
{
    $institute = Institute::findOrFail($id);
    
    // Get all courses for this institute with their available schedules
    $allCourses = Course::where('institute_id', $id)
                     ->with(['availableSchedules' => function($query) {
                         $query->orderBy('date', 'asc')
                               ->orderBy('start_time', 'asc');
                     }])
                     ->has('availableSchedules')
                     ->get();
    
    // Group by course_module
    $courses = $allCourses->groupBy('course_module');
    
    return view('frontend.courses.modules', compact('institute', 'courses'));
}

public function showBooking($courseId)
{
    $course = Course::with(['institute', 'availableSchedules' => function($query) {
        $query->orderBy('date', 'asc')
              ->orderBy('start_time', 'asc');
    }])->findOrFail($courseId);
    
    // Get unique dates, times, and levels from available schedules
    $availableDates = $course->availableSchedules->pluck('date')->unique()->sort()->values();
    $availableLevels = $course->availableSchedules->pluck('course_level')->unique()->sort()->values();
    
    // Group schedules by date for time selection and prepare for JSON
    $schedulesByDate = $course->availableSchedules->groupBy(function($schedule) {
        return $schedule->date->format('Y-m-d');
    })->map(function($schedules, $date) {
        return [
            'date' => $date,
            'schedules' => $schedules->map(function($s) {
                return [
                    'id' => $s->id,
                    'start_time' => \Carbon\Carbon::parse($s->start_time)->format('H:i'),
                    'end_time' => \Carbon\Carbon::parse($s->end_time)->format('H:i'),
                    'course_level' => $s->course_level,
                    'price' => $s->price
                ];
            })->values()
        ];
    })->values();
    
    // Prepare dates as simple array for JSON
    $availableDatesArray = $availableDates->map(function($date) {
        return $date->format('Y-m-d');
    })->toArray();
    
    return view('frontend.courses.booking', compact('course', 'availableDates', 'availableLevels', 'schedulesByDate', 'availableDatesArray'));
}

}

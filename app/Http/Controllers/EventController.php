<?php
// app/Http/Controllers/EventController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Location;
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

    return view('academy', compact('events', 'locations'));
}

}

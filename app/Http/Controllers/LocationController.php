<?php
namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authorizeAdminModuleAccess(['all_locations', 'Create_locations']);
            return $next($request);
        });
    }

    private function authorizeAdminModuleAccess(array $abilities): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if ($user->user_type === 'admin') {
            return;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
            return;
        }

        foreach ($abilities as $ability) {
            try {
                if ($user->can($ability)) {
                    return;
                }
            } catch (\Throwable $e) {
            }
        }

        abort(403);
    }

    public function index()
    {
        $locations = Location::orderBy('id', 'desc')->get();
        return view('backend.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('backend.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Location::create([
            'name' => $request->name,
        ]);
        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        return view('backend.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $location->update([
            'name' => $request->name,
        ]);
        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}

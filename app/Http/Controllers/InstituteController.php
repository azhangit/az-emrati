<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $institutes = Institute::orderBy('name', 'asc');
        
        if ($request->has('search')) {
            $sort_search = $request->search;
            $institutes = $institutes->where('name', 'like', '%'.$sort_search.'%');
        }
        
        $institutes = $institutes->paginate(15);
        return view('backend.institutes.index', compact('institutes', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.institutes.create');
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
            'name' => 'required|string|max:255',
            'image' => 'required',
        ]);

        $institute = new Institute;
        $institute->name = $request->name;
        $institute->image = $request->image;
        $institute->save();

        flash(translate('Institute has been inserted successfully'))->success();
        return redirect()->route('institutes.index');
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
        $institute = Institute::findOrFail($id);
        return view('backend.institutes.edit', compact('institute'));
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
            'name' => 'required|string|max:255',
            'image' => 'required',
        ]);

        $institute = Institute::findOrFail($id);
        $institute->name = $request->name;
        $institute->image = $request->image;
        $institute->save();

        flash(translate('Institute has been updated successfully'))->success();
        return redirect()->route('institutes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $institute = Institute::findOrFail($id);
        $institute->delete();

        flash(translate('Institute has been deleted successfully'))->success();
        return redirect()->route('institutes.index');
    }
}


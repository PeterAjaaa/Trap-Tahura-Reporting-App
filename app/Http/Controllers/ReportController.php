<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function showSharedReport($token) {}

    public function showPhoto($id)
    {
        $report = Report::find($id);

        $path = 'reports/photos/' . $report->photo;

        return Storage::disk()->response($path);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'description' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->hashName();
            $request->file('photo')->storeAs('reports/photos', $photoName);
            $data = $request->all();
            $data['photo'] = $photoName;
        } else {
            $data = $request->all();
        }

        $report = Report::create($data);

        return redirect()->route('reports.share', ['token' => $report->shareable_token])->with('success', 'Report created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($token)
    {
        $report = Report::where('shareable_token', $token)->first();

        if (!$report) {
            abort(404, 'Report not found');
        }

        return view('reports.tracking', ['report' => $report]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}

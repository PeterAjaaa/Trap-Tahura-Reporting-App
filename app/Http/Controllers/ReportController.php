<?php

namespace App\Http\Controllers;

use App\Events\ReportCreated;
use App\Events\ReportClosed;

use App\Models\Report;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,Assigned,In Progress,Resolved,Closed',
        ]);

        $report = Report::findOrFail($id);
        $report->status = $request->input('status');
        $report->save();

        return redirect()->back()->with('success', 'Report status updated successfully!');
    }


    public function closeReport($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'Closed';
        $report->save();

        event(new ReportClosed($report));

        return redirect()->back()->with('success', 'Report closed successfully.');
    }

    public function showPhoto($id)
    {
        $report = Report::find($id);

        $path = 'reports/photos/' . $report->photo;

        return Storage::disk()->response($path);
    }


    public function assignReportsAutomatically($report)
    {
        $admin = User::withCount('assignedReports')
            ->orderBy('assigned_reports_count', 'asc')
            ->first();

        if ($admin) {
            $report->update([
                'admin_id' => $admin->id,
                'status' => 'Assigned',
            ]);

            event(new ReportCreated($report));
        }
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
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->hashName();
            $request->file('photo')->storeAs('reports/photos', $photoName);
            $data = $request->all();
            $data['priority'] = Report::getPriority($request->input('type'));
            $data['photo'] = $photoName;
        } else {
            $data = $request->all();
            $data['priority'] = Report::getPriority($request->input('type'));
        }

        $report = Report::create($data);
        $this->assignReportsAutomatically($report);

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

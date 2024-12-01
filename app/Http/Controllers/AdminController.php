<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::where('admin_id', auth()->id())
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($report) {
                return $report->status === 'Closed' ? 'Closed' : 'Active';
            });

        return view('admin.dashboard', [
            'activeReports' => $reports['Active'] ?? collect(),
            'closedReports' => $reports['Closed'] ?? collect(),
        ]);
    }

    public function createNewAdmin()
    {
        return view('admin.add_admin');
    }

    public function saveNewAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'login_id' => 'required|string|unique:users,login_id',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8|same:password',
            'role' => 'required|string|in:admin,master',
        ]);

        User::create([
            'name' => $request->input('name'),
            'login_id' => $request->input('login_id'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
        ]);

        if ($request->input('role') === 'master') {
            return redirect()->route('master.admin.create')->with('success', 'New master admin created successfully!');
        } else {
            return redirect()->route('master.admin.create')->with('success', 'New admin created successfully!');
        }
    }
}

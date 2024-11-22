<?php

namespace App\Http\Controllers;

use App\Models\Report;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::where('admin_id', auth()->id())
            ->orderBy('priority', 'desc')
            ->get();

        return view('admin.dashboard', compact('reports'));
    }
}

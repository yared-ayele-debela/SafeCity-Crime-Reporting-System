<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SafeCity\Assignment;
use App\Models\SafeCity\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //

     public function __construct()
    {
        $this->middleware('admin.permission:view_report')->only('index');
        $this->middleware('admin.permission:view_report')->only('show');
        $this->middleware('admin.permission:edit_report')->only(methods: 'updateStatus');
        // $this->middleware('admin.permission:delete_report')->only('destroy');
    }
   public function index(Request $request)
{
    // Base query depending on user type
    if (Auth::guard('admin')->user()->type == 'admin' || Auth::guard('admin')->user()->type == 'Admins') {
  $query = Report::with(['user', 'category', 'assignment.officer']);
    } else {
          $query = Report::whereHas('assignment', function ($q) {
            $q->where('officer_id', Auth::guard('admin')->user()->id);
        })->with(['user', 'category', 'assignment.officer']);
    }

    // Filters
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    // Paginate the final query
    $reports = $query->latest()->paginate(10);

    if ($request->ajax()) {
        return view('admin.reports.partials.report_rows', compact('reports'))->render();
    }

    $categories = \App\Models\Category::all();

    return view('admin.reports.index', compact('reports', 'categories'));
}



    public function show($id)
    {
        $officers=Admin::where('type','officer')->get();
        $report = Report::with(['user', 'category', 'assignment.officer'])
                        ->findOrFail($id);

        return view('admin.reports.show', compact('report','officers'));
    }

    public function updateStatus(Request $request, Report $report)
{
    // dd($report);
    $request->validate([
        'status' => 'required|in:open,resolved,pending,rejected,in_progress', // add all allowed statuses here
    ]);

    $report->status = $request->status;
    $report->save();

    return redirect()->route('admin.reports.show', $report->id)->with('success_message', 'Report status updated successfully.');

}

public function store(Request $request)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'officer_id' => 'required|exists:admins,id',
        ]);

        Assignment::create([
            'report_id' => $request->report_id,
            'officer_id' => $request->officer_id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return back()->with( 'success_message', 'Report assigned to officer successfully.');
    }
}

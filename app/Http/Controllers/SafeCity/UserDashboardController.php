<?php

namespace App\Http\Controllers\SafeCity;

use App\Http\Controllers\Controller;
use App\Models\SafeCity\Report;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    //
    public function dashboard()
    {
        $user = auth()->user();
        $total_reports=Report::with('files')->where("user_id", $user->id)->count();
        $open_reports = Report::with('files')->where("user_id", $user->id)->where("status", "open")->count();
        $close_reports = Report::with('files')->where("user_id", $user->id)->where("status", "closed")->count();
        $pending_reports = Report::with('files')->where("user_id", $user->id)->where("status", "pending")->count();
        $in_progress_reports = Report::with('files')->where("user_id", $user->id)->where("status", "in_progress")->count();
        $rejected_reports = Report::with('files')->where("user_id", $user->id)->where("status", "rejected")->count();
        $resolved_reports = Report::with('files')->where("user_id", $user->id)->where("status", "resolved")->count();

        $recent_reports = Report::with('files')->where('user_id', $user->id)
                            ->latest()
                            ->paginate(10);

        return view('frontend.user.dashboard.index',compact('total_reports', 'open_reports', 'close_reports', 'pending_reports', 'in_progress_reports', 'rejected_reports', 'resolved_reports', 'recent_reports', 'user'));
    }

    public function show($id)
{
    $report = Report::with(['category', 'comments', 'assignment'])->findOrFail($id);

    // Optional: Only allow owner or admin/officer to view
    if (auth()->user()->role === 'user' && auth()->id() !== $report->user_id) {
        abort(403);
    }

    return view('frontend.user.dashboard.reports.show', compact('report'));
}
    public function profile()
    {
        $user = auth()->user();

        // This method can be used to return the user profile page
        return view('frontend.user.dashboard.user_profile.detail',compact('user'));
    }

    public function reports()
    {
        $reports= Report::with('files')->where('user_id', auth()->id())
            ->with(['category'])
            ->latest()
            ->paginate(10);
        // This method can be used to return the user reports page
        return view('frontend.user.dashboard.reports.index',compact('reports'));
    }
    public function notifications()
    {
        // This method can be used to return the user notifications page
        return view('safe_city.user_dashboard.notifications');
    }
}
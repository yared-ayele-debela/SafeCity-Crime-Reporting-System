@extends('admindashboard.layouts')
@section('dashboard')
<div class="container-fluid py-4">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb bg-white shadow-sm py-3 px-4 rounded d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-outline-primary btn-sm d-flex align-items-center" onclick="history.back()">
            <i class="bi bi-arrow-left me-2"></i>
            <span>Back</span>
        </button>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('admin/dashboard') }}">
                    <i class="bi bi-house-door me-1"></i> Home
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard Overview
            </li>
        </ol>
    </nav>

    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1">Admin Dashboard</h1>
                        <p class="text-muted mb-0">Welcome back! Here's an overview of your system statistics.</p>
                    </div>
                    <div class="text-end">
                        <p class="text-muted mb-0">Today's Date</p>
                        <p class="h5 mb-0">{{ date('l, F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        @php
            $cards = [
                ['title' => 'Total Users', 'count' => $totalUsers, 'color' => 'primary', 'icon' => 'bi-people-fill'],
                ['title' => 'Total Reports', 'count' => $totalReports, 'color' => 'info', 'icon' => 'bi-file-earmark-text-fill'],
                ['title' => 'Open Reports', 'count' => $openReports, 'color' => 'warning', 'icon' => 'bi-exclamation-triangle-fill'],
                ['title' => 'Resolved Reports', 'count' => $resolvedReports, 'color' => 'success', 'icon' => 'bi-check-circle-fill'],
                ['title' => 'Officers', 'count' => $totalOfficers, 'color' => 'dark', 'icon' => 'bi-shield-fill'],
                ['title' => 'Admins', 'count' => $totalAdmins, 'color' => 'secondary', 'icon' => 'bi-person-badge-fill'],
                ['title' => 'Officer Departments', 'count' => $total_officer_departments, 'color' => 'danger', 'icon' => 'bi-building'],
                ['title' => 'Total Roles', 'count' => $total_role, 'color' => 'primary', 'icon' => 'bi-person-check-fill'],
                ['title' => 'Total Permissions', 'count' => $total_permission, 'color' => 'success', 'icon' => 'bi-key-fill'],
            ];
        @endphp

        @foreach($cards as $index => $card)
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card bg-white border-0 shadow-sm h-100 overflow-hidden stat-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1 fw-bold text-uppercase">{{ $card['title'] }}</p>
                            <h3 class="mb-0 fw-bold">{{ $card['count'] }}</h3>
                        </div>
                        <div class="icon-container bg-{{ $card['color'] }} bg-opacity-10 text-{{ $card['color'] }} rounded-circle p-3">
                            <i class="bi {{ $card['icon'] }} fs-4"></i>
                        </div>
                    </div>
                </div>
                <div class="progress bg-{{ $card['color'] }} bg-opacity-10" style="height: 4px;">
                    <div class="progress-bar bg-{{ $card['color'] }}" role="progressbar" style="width: {{ min(100, ($card['count'] / max(1, $totalUsers)) * 100) }}%;" aria-valuenow="{{ $card['count'] }}" aria-valuemin="0" aria-valuemax="{{ $totalUsers }}"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <div class="col-xl-8 mb-4">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                    <h5 class="mb-0">Reports Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="reportsChart" height="300" style="height: 300px; width: 100%; border: 1px solid #ccc;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-4">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                    <h5 class="mb-0">Report Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .icon-container {
        transition: transform 0.3s ease;
    }

    .stat-card:hover .icon-container {
        transform: scale(1.1);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reports Overview Chart
        const reportsCtx = document.getElementById('reportsChart').getContext('2d');
        const reportsChart = new Chart(reportsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Total Reports',
                    data: [65, 59, 80, 81, 56, 55, 70, 75, 80, 85, 90, 95],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Resolved Reports',
                    data: [45, 49, 60, 71, 46, 45, 60, 65, 70, 75, 80, 85],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Report Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'In Progress', 'Resolved', 'Closed'],
                datasets: [{
                    data: [{{ $openReports }}, 30, {{ $resolvedReports }}, 20],
                    backgroundColor: [
                        '#ffc107',
                        '#0dcaf0',
                        '#198754',
                        '#6c757d'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>
@endsection

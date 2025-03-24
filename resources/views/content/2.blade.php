@extends('layouts.main')
@section('content')
<div class="row g-4">
    <!-- Student Analytics Card -->
    <div class="col-lg-6">
        <div class="card gradient-card bg-primary-dark">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="text-white mb-1">Student Analytics</h3>
                        <span class="text-white-50">28.5% Enrollment Growth</span>
                    </div>
                    <div class="avatar avatar-lg bg-white-10 rounded-circle p-2">
                        <i class="ti ti-school text-white fs-4"></i>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-6">
                        <div class="stats-box bg-white-10 p-3 rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ti ti-users text-white fs-5"></i>
                                <span class="text-white">Active Students</span>
                            </div>
                            <h2 class="text-white mb-0">1.2k</h2>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="stats-box bg-white-10 p-3 rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ti ti-certificate text-white fs-5"></i>
                                <span class="text-white">Completed Courses</span>
                            </div>
                            <h2 class="text-white mb-0">48</h2>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <canvas id="enrollmentChart" style="height: 150px"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Engagement Metrics -->
    <div class="col-lg-6">
        <div class="row g-4">
            <div class="col-12">
                <div class="card sparkle-card bg-gradient-purple">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-white mb-1">Daily Enrollments</h4>
                                <span class="text-white-50">This Month</span>
                            </div>
                            <i class="ti ti-chart-line text-white fs-8 opacity-25"></i>
                        </div>
                        <h1 class="text-white mt-3 mb-0">284</h1>
                        <div class="mt-4">
                            <canvas id="enrollmentTrend" style="height: 80px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card hover-scale">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Course Progress</h5>
                            <i class="ti ti-books text-primary fs-5"></i>
                        </div>
                        <div class="progress-list">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-sm bg-primary text-white rounded-2">
                                        WD
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Web Development</span>
                                        <span>65%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 65%"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add more courses -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Learning Resources -->
    <div class="col-lg-8">
        <div class="card hover-scale">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Learning Resources</h4>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="resource-card bg-primary-light p-3 rounded-3 h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar avatar-lg bg-primary text-white rounded-2">
                                    <i class="ti ti-video"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Video Lectures</h5>
                                    <span class="text-muted fs-sm">48 New Uploads</span>
                                </div>
                            </div>
                            <div class="bg-white-10 p-2 rounded-2">
                                <span class="text-primary fs-sm">+15% from last month</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add other resource cards -->
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4">
        <div class="card bg-gradient-warning hover-scale">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="text-white mb-0">Student Engagement</h4>
                        <span class="text-white-50">Active Participation</span>
                    </div>
                    <i class="ti ti-brand-google-analytics text-white fs-4"></i>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="avatar avatar-sm bg-white-10 text-white rounded-1">
                            <i class="ti ti-clock"></i>
                        </div>
                        <h3 class="text-white mt-2">4.8h</h3>
                        <span class="text-white-50">Avg. Study Time</span>
                    </div>
                    <!-- Add more stats -->
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-12">
        <div class="card hover-scale">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Recent Enrollments</h4>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Progress</th>
                                <th>Enrollment Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm">
                                            <img src="assets/img/avatars/1.png" alt="Avatar">
                                        </div>
                                        John Doe
                                    </div>
                                </td>
                                <td>Web Development</td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-gradient-primary" style="width: 65%"></div>
                                    </div>
                                </td>
                                <td>2023-08-15</td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-outline-primary">
                                        <i class="ti ti-external-link"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Add more rows -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .gradient-card {
        background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
        border: none;
        border-radius: 1rem;
    }
    
    .hover-scale {
        transition: transform 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .hover-scale:hover {
        transform: translateY(-5px);
    }
    
    .bg-primary-dark {
        background: #1a237e;
    }
    
    .bg-primary-light {
        background: rgba(26, 35, 126, 0.1);
    }
    
    .sparkle-card {
        position: relative;
        overflow: hidden;
    }
    
    .sparkle-card::after {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 20%, rgba(255,255,255,0.1) 50%, transparent 80%);
        animation: sparkle 4s infinite;
    }
    
    @keyframes sparkle {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

@endsection
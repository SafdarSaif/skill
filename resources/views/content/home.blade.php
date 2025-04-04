@extends('layouts.main')
@section('content')
<div class="row g-4">
    <!-- Main Stats Row -->
    <div class="col-12">
        <div class="row g-4">
            <!-- Total Students -->
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm bg-soft-sky">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-soft-primary p-3 rounded me-3">
                                <i class="ti ti-users fs-4 text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 text-primary-dark">Total Students</h5>
                                {{-- <h2 class="mb-0 text-primary">1,842</h2> --}}
                                <h2 class="mb-0 text-primary">{{ $studentCount }}</h2> 

                            </div>
                        </div>
                        {{-- <div class="mt-3">
                            <span class="badge bg-soft-success text-success">+22.4%</span>
                            <span class="text-muted ms-2">From last month</span>
                        </div> --}}
                        <div class="mt-3">
                            <span class="badge {{ $studentGrowth >= 0 ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                                {{ $studentGrowth >= 0 ? '+' : '' }}{{ number_format($studentGrowth, 1) }}%
                            </span>
                            <span class="text-muted ms-2">From last month</span>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Active Courses -->
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm bg-soft-lavender">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-soft-info p-3 rounded me-3">
                                <i class="ti ti-book fs-4 text-info"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 text-info-dark">Active Courses</h5>
                                {{-- <h2 class="mb-0 text-info">58</h2> --}}
                                <h2 class="mb-0 text-info">{{$courseCount}}</h2>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-soft-success text-success">+12.4%</span>
                            <span class="text-muted ms-2">New additions</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Rate -->
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm bg-soft-mint">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-soft-success p-3 rounded me-3">
                                <i class="ti ti-certificate fs-4 text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 text-success-dark">Users</h5>
                                {{-- <h2 class="mb-0 text-success">86%</h2> --}}
                                <h2 class="mb-0 text-success">{{ $userCount }}</h2>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-soft-danger text-danger">-2.8%</span>
                            <span class="text-muted ms-2">From last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm bg-soft-peach">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-soft-warning p-3 rounded me-3">
                                <i class="ti ti-progress fs-4 text-warning"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 text-warning-dark">Total Revenue</h5>
                                {{-- <h2 class="mb-0 text-warning">78%</h2> --}}
                                {{-- <h2 class="mb-0 text-warning">₹{{ number_format($totalRevenue, 2, '.', ',') }}</h2> --}}
                                <h2 class="mb-0 text-warning">₹{{ number_format($totalRevenue, 0) }}</h2>


                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-soft-success text-success">+4.2%</span>
                            <span class="text-muted ms-2">From last month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm bg-soft-cloud">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 text-primary-dark">Enrollment Trends</h5>
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">View Report</a>
                            <a class="dropdown-item" href="#">Export Data</a>
                        </div>
                    </div>
                </div>
                <canvas id="enrollmentChart" style="height: 250px"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm bg-soft-lavender">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 text-info-dark">Recent Enrollments</h5>
                    <a href="{{ route('student') }}" class="btn btn-sm btn-soft-primary">View All</a>
                </div>

                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex align-items-center px-0 bg-transparent">
                        <div class="avatar avatar-sm bg-soft-primary rounded me-3">
                            JD
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">John Doe</h6>
                            <small class="text-muted">Web Development</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-soft-success text-success">65%</span>
                            <div class="text-muted small">2 days ago</div>
                        </div>
                    </div>
                    <!-- Add more list items -->
                </div>
            </div>
        </div>
    </div>

    <!-- Course Progress Section -->
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-soft-mint">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 text-success-dark">Course Progress</h5>
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Manage Courses</a>
                            <a class="dropdown-item" href="#">View All</a>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-white rounded-3">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm bg-soft-primary text-primary rounded me-3">
                                    WD
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">Web Development</h6>
                                    <small class="text-primary">65%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add more courses -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 1rem;
        transition: transform 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
    }

    /* Soft Color Schemes */
    .bg-soft-sky { background-color: #f0f9ff; }
    .bg-soft-lavender { background-color: #f8f5ff; }
    .bg-soft-mint { background-color: #f0fdf4; }
    .bg-soft-peach { background-color: #fff1f2; }
    .bg-soft-cloud { background-color: #f8fafc; }

    .text-primary-dark { color: #1d4ed8; }
    .text-info-dark { color: #3b82f6; }
    .text-success-dark { color: #059669; }
    .text-warning-dark { color: #d97706; }

    .btn-soft-primary {
        background-color: rgba(29,78,216,0.1);
        color: #1d4ed8;
        border: none;
    }

    .btn-soft-primary:hover {
        background-color: rgba(29,78,216,0.2);
    }

    .bg-soft-primary { background-color: rgba(29,78,216,0.1); }
    .bg-soft-success { background-color: rgba(5,150,105,0.1); }
    .bg-soft-info { background-color: rgba(59,130,246,0.1); }
    .bg-soft-warning { background-color: rgba(217,119,6,0.1); }
    .bg-soft-danger { background-color: rgba(220,38,38,0.1); }

    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .progress-bar {
        background-color: #1d4ed8;
        border-radius: 4px;
    }

    .list-group-item {
        background-color: transparent;
        border-color: rgba(0,0,0,0.05);
    }
</style>
@endsection
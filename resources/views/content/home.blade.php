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
                                    <h2 class="mb-0 text-primary">{{ $studentCount }}</h2>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span
                                    class="badge {{ $studentGrowth >= 0 ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
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
                                    <h2 class="mb-0 text-info">{{ $courseCount }}</h2>
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
        {{-- <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-soft-cloud">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 text-primary-dark">Enrollment Trends</h5>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#" id="weeklyBtn">Weekly</a>
                                <a class="dropdown-item" href="#" id="monthlyBtn">Monthly</a>
                                
                            </div>
                        </div>
                    </div>
                    <canvas id="enrollmentChart" style="height: 250px"></canvas>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-soft-cloud">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 text-primary-dark">Enrollment Trends</h5>
                        <div class="d-flex">
                            <select id="yearFilter" class="form-select me-2">
                                <!-- Years will be populated dynamically -->
                            </select>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#" id="weeklyBtn">Weekly</a>
                                    <a class="dropdown-item" href="#" id="monthlyBtn">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <canvas id="enrollmentChart" style="height: 250px"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods Bar Chart -->
        {{-- <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-soft-lavender">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 text-info-dark">Payment Methods</h5>
                        <div>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-soft-primary active"
                                    id="paymentWeeklyBtn">Weekly</button>
                                <button type="button" class="btn btn-soft-primary" id="paymentMonthlyBtn">Monthly</button>
                            </div>
                            <a href="{{ route('payment') }}" class="btn btn-sm btn-soft-primary ms-2">View All</a>
                        </div>
                    </div>

                    <div class="position-relative" style="height: 250px;">
                        <canvas id="paymentChart"></canvas>
                    </div>

                    <div class="mt-4">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="payment-method">
                                    <span class="badge-dot bg-primary"></span>
                                    <small class="text-muted">Credit Card</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method">
                                    <span class="badge-dot bg-success"></span>
                                    <small class="text-muted">PayPal</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method">
                                    <span class="badge-dot bg-warning"></span>
                                    <small class="text-muted">Bank Transfer</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method">
                                    <span class="badge-dot bg-secondary"></span>
                                    <small class="text-muted">Other</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-white rounded">
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-primary fw-bold">Payment Methods</h5>
                        <div class="d-flex align-items-center">
                            <!-- Year Dropdown -->
                            <select id="yearSelect" class="form-select form-select-sm me-2 border-primary text-primary">
                                <!-- Years will be populated dynamically -->
                            </select>
                            <!-- Weekly & Monthly Toggle -->
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-primary active" id="paymentWeeklyBtn">Weekly</button>
                                <button type="button" class="btn btn-outline-primary"
                                    id="paymentMonthlyBtn">Monthly</button>
                            </div>
                            <!-- View All Button -->
                            <a href="{{ route('payment') }}" class="btn btn-sm btn-outline-primary ms-2">View All</a>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="position-relative" style="height: 270px;">
                        <canvas id="paymentChart"></canvas>
                    </div>
                    {{-- <div class="mt-4">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="payment-method">
                                    <span class="badge-dot bg-primary"></span>
                                    <small class="text-muted">Credit Card</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method">
                                    <span class="badge-dot bg-success"></span>
                                    <small class="text-muted">PayPal</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method">
                                    <span class="badge-dot bg-warning"></span>
                                    <small class="text-muted">Bank Transfer</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method">
                                    <span class="badge-dot bg-secondary"></span>
                                    <small class="text-muted">Other</small>
                                </div>
                            </div>
                        </div>
                    </div> --}}
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
                        @foreach ($courses as $course)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-white rounded-3">
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-sm bg-soft-primary text-primary rounded me-3">
                                            {{ substr($course->name, 0, 2) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-2">
                                            <h6 class="mb-0">{{ $course->name }}</h6>
                                            <small class="text-primary">{{ $course->completion_rate }}%</small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ $course->completion_rate }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 1rem;
            transition: transform 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        /* Soft Color Schemes */
        .bg-soft-sky {
            background-color: #f0f9ff;
        }

        .bg-soft-lavender {
            background-color: #f8f5ff;
        }

        .bg-soft-mint {
            background-color: #f0fdf4;
        }

        .bg-soft-peach {
            background-color: #fff1f2;
        }

        .bg-soft-cloud {
            background-color: #f8fafc;
        }

        .text-primary-dark {
            color: #1d4ed8;
        }

        .text-info-dark {
            color: #3b82f6;
        }

        .text-success-dark {
            color: #059669;
        }

        .text-warning-dark {
            color: #d97706;
        }

        .btn-soft-primary {
            background-color: rgba(29, 78, 216, 0.1);
            color: #1d4ed8;
            border: none;
        }

        .btn-soft-primary:hover {
            background-color: rgba(29, 78, 216, 0.2);
        }

        .bg-soft-primary {
            background-color: rgba(29, 78, 216, 0.1);
        }

        .bg-soft-success {
            background-color: rgba(5, 150, 105, 0.1);
        }

        .bg-soft-info {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .bg-soft-warning {
            background-color: rgba(217, 119, 6, 0.1);
        }

        .bg-soft-danger {
            background-color: rgba(220, 38, 38, 0.1);
        }

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
            border-color: rgba(0, 0, 0, 0.05);
        }


        /* css for piechart */
        .badge-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .payment-method {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-group .btn.active {
            background-color: rgba(29, 78, 216, 0.2);
            color: #1d4ed8;
            font-weight: 500;
        }
    </style>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('enrollmentChart').getContext('2d');
            let enrollmentChart;

            function populateYearFilter() {
                const yearFilter = document.getElementById('yearFilter');
                const currentYear = new Date().getFullYear();
                for (let year = currentYear; year >= currentYear - 5; year--) {
                    let option = new Option(year, year);
                    yearFilter.appendChild(option);
                }
                yearFilter.value = currentYear; // Default to current year
            }

            function loadChartData(type, year) {
                fetch(`/dashboard/enrollment-data?type=${type}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        const chartData = {
                            labels: data.labels,
                            datasets: [{
                                label: `${type.charAt(0).toUpperCase() + type.slice(1)} Enrollments (${year})`,
                                data: data.values,
                                backgroundColor: 'rgba(29, 78, 216, 0.2)',
                                borderColor: 'rgba(29, 78, 216, 1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }]
                        };

                        const config = {
                            type: 'line',
                            data: chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        ticks: {
                                            stepSize: 10
                                        }
                                    }
                                },
                                interaction: {
                                    mode: 'nearest',
                                    axis: 'x',
                                    intersect: false
                                }
                            }
                        };

                        if (enrollmentChart) {
                            enrollmentChart.destroy();
                        }
                        enrollmentChart = new Chart(ctx, config);
                    });
            }

            populateYearFilter();
            const selectedYear = document.getElementById('yearFilter').value;
            loadChartData('weekly', selectedYear);

            document.getElementById('weeklyBtn').addEventListener('click', function(e) {
                e.preventDefault();
                const selectedYear = document.getElementById('yearFilter').value;
                loadChartData('weekly', selectedYear);
            });

            document.getElementById('monthlyBtn').addEventListener('click', function(e) {
                e.preventDefault();
                const selectedYear = document.getElementById('yearFilter').value;
                loadChartData('monthly', selectedYear);
            });

            document.getElementById('yearFilter').addEventListener('change', function() {
                const type = document.querySelector('.dropdown-menu .active')?.id || 'weekly';
                loadChartData(type, this.value);
            });
        });
    </script>


    {{-- // Payment Methods Chart --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const weeklyPaymentData = {
                labels: ['Credit Card', 'PayPal', 'Bank Transfer', 'Other'],
                datasets: [{
                    label: 'Weekly Payments',
                    data: [45, 30, 20, 5],
                    backgroundColor: [
                        'rgba(29, 78, 216, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ],
                    borderColor: [
                        'rgba(29, 78, 216, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(156, 163, 175, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            const monthlyPaymentData = {
                labels: ['Credit Card', 'PayPal', 'Bank Transfer', 'Other'],
                datasets: [{
                    label: 'Monthly Payments',
                    data: [220, 150, 120, 30],
                    backgroundColor: [
                        'rgba(29, 78, 216, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ],
                    borderColor: [
                        'rgba(29, 78, 216, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(156, 163, 175, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Chart configuration for bar chart
            const paymentConfig = {
                type: 'bar',
                data: weeklyPaymentData, 
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.raw || 0;
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            };

            // Initialize chart
            const paymentCtx = document.getElementById('paymentChart').getContext('2d');
            const paymentChart = new Chart(paymentCtx, paymentConfig);

            document.getElementById('paymentWeeklyBtn').addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.add('active');
                document.getElementById('paymentMonthlyBtn').classList.remove('active');
                paymentChart.data = weeklyPaymentData;
                paymentChart.update();
            });

            document.getElementById('paymentMonthlyBtn').addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.add('active');
                document.getElementById('paymentWeeklyBtn').classList.remove('active');
                paymentChart.data = monthlyPaymentData;
                paymentChart.update();
            });
        });
    </script> --}}

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const weeklyPaymentData = {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Weekly Payments',
                    data: [120, 150, 180, 100],
                    backgroundColor: [
                        'rgba(29, 78, 216, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ],
                    borderColor: [
                        'rgba(29, 78, 216, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(156, 163, 175, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            const monthlyPaymentData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Payments',
                    data: [500, 620, 700, 480, 540, 600, 650, 720, 680, 750, 770, 820],
                    backgroundColor: [
                        'rgba(29, 78, 216, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ],
                    borderColor: [
                        'rgba(29, 78, 216, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(156, 163, 175, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Chart configuration
            const paymentConfig = {
                type: 'bar',
                data: weeklyPaymentData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.raw || 0;
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            };

            // Initialize chart
            const paymentCtx = document.getElementById('paymentChart').getContext('2d');
            const paymentChart = new Chart(paymentCtx, paymentConfig);

            document.getElementById('paymentWeeklyBtn').addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.add('active');
                document.getElementById('paymentMonthlyBtn').classList.remove('active');
                paymentChart.data = weeklyPaymentData;
                paymentChart.update();
            });

            document.getElementById('paymentMonthlyBtn').addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.add('active');
                document.getElementById('paymentWeeklyBtn').classList.remove('active');
                paymentChart.data = monthlyPaymentData;
                paymentChart.update();
            });
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('paymentChart').getContext('2d');
            let paymentChart;

            function fetchPaymentData(type = 'weekly', year = new Date().getFullYear()) {
                fetch(`/payment-data?type=${type}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        updateChart(data.labels, data.values, type);
                    });
            }

            function updateChart(labels, values, type) {
                if (paymentChart) {
                    paymentChart.destroy(); // Destroy previous instance
                }

                paymentChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `${type.charAt(0).toUpperCase() + type.slice(1)} Payments`,
                            data: values,
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `₹${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    callback: (value) => `₹${value}`
                                }
                            }
                        }
                    }
                });
            }

            // Fetch initial data
            fetchPaymentData();

            // Handle button clicks
            document.getElementById('paymentWeeklyBtn').addEventListener('click', function() {
                fetchPaymentData('weekly');
                this.classList.add('active');
                document.getElementById('paymentMonthlyBtn').classList.remove('active');
            });

            document.getElementById('paymentMonthlyBtn').addEventListener('click', function() {
                fetchPaymentData('monthly');
                this.classList.add('active');
                document.getElementById('paymentWeeklyBtn').classList.remove('active');
            });
        });
    </script>
@endsection

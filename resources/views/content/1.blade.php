@extends('layouts.main')
@section('content')
<div class="row g-6">
    <!-- Student Analytics -->
    <div class="col-lg-6">
      <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg" id="swiper-with-pagination-cards">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="row">
              <div class="col-12">
                <h5 class="text-white mb-0">Student Analytics</h5>
                <small>Total 28.5% Enrollment Growth</small>
              </div>
              <div class="row">
                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                  <h6 class="text-white mt-0 mt-md-3 mb-4">Enrollments</h6>
                  <div class="row">
                    <div class="col-6">
                      <ul class="list-unstyled mb-0">
                        <li class="d-flex mb-4 align-items-center">
                          <p class="mb-0 fw-medium me-2 website-analytics-text-bg">1.2k</p>
                          <p class="mb-0">Active Students</p>
                        </li>
                        <li class="d-flex align-items-center">
                          <p class="mb-0 fw-medium me-2 website-analytics-text-bg">324</p>
                          <p class="mb-0">New Enrollments</p>
                        </li>
                      </ul>
                    </div>
                    <div class="col-6">
                      <ul class="list-unstyled mb-0">
                        <li class="d-flex mb-4 align-items-center">
                          <p class="mb-0 fw-medium me-2 website-analytics-text-bg">86%</p>
                          <p class="mb-0">Course Progress</p>
                        </li>
                        <li class="d-flex align-items-center">
                          <p class="mb-0 fw-medium me-2 website-analytics-text-bg">48</p>
                          <p class="mb-0">Completed Courses</p>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                  <img src="{{ asset('assets/img/illustrations/student-analytics.png') }}" alt="Student Analytics" height="150">
                </div>
              </div>
            </div>
          </div>
          <!-- Add additional slides if needed -->
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
    <!--/ Student Analytics -->

    <!-- Daily Enrollments -->
    <div class="col-xl-3 col-sm-6">
      <div class="card h-100">
        <div class="card-header pb-0">
          <h5 class="mb-3 card-title">Daily Enrollments</h5>
          <p class="mb-0 text-body">Total This Month</p>
          <h4 class="mb-0">284</h4>
        </div>
        <div class="card-body px-0">
          <div id="dailyEnrollmentsChart"></div>
        </div>
      </div>
    </div>
    <!--/ Daily Enrollments -->

    <!-- Enrollment Overview -->
    <div class="col-xl-3 col-sm-6">
      <div class="card h-100">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <p class="mb-0 text-body">Enrollment Overview</p>
            <p class="card-text fw-medium text-success">+22.4%</p>
          </div>
          <h4 class="card-title mb-1">1,842</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="d-flex gap-2 align-items-center mb-2">
                <span class="badge bg-label-info p-1 rounded"><i class="ti ti-users ti-sm"></i></span>
                <p class="mb-0">New</p>
              </div>
              <h5 class="mb-0 pt-1">62.2%</h5>
              <small class="text-muted">324</small>
            </div>
            <div class="col-4">
              <div class="divider divider-vertical">
                <div class="divider-text">
                  <span class="badge-divider-bg bg-label-secondary">VS</span>
                </div>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                <p class="mb-0">Active</p>
                <span class="badge bg-label-primary p-1 rounded"><i class="ti ti-user-check ti-sm"></i></span>
              </div>
              <h5 class="mb-0 pt-1">85.5%</h5>
              <small class="text-muted">1,572</small>
            </div>
          </div>
          <div class="d-flex align-items-center mt-6">
            <div class="progress w-100" style="height: 10px;">
              <div class="progress-bar bg-info" style="width: 70%" role="progressbar"></div>
              <div class="progress-bar bg-primary" role="progressbar" style="width: 30%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Enrollment Overview -->

    <!-- Course Statistics -->
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header pb-0 d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="mb-1">Course Statistics</h5>
            <p class="card-subtitle">Monthly Enrollment Overview</p>
          </div>
          <div class="dropdown">
            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="earningReportsId">
              <i class="ti ti-dots-vertical ti-md text-muted"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="{{ route('course') }}">View Courses</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row align-items-center g-md-8">
            <div class="col-12 col-md-5 d-flex flex-column">
              <div class="d-flex gap-2 align-items-center mb-3 flex-wrap">
                <h2 class="mb-0">58</h2>
                <div class="badge rounded bg-label-success">+12.4%</div>
              </div>
              <small class="text-body">Active courses with enrollments</small>
            </div>
            <div class="col-12 col-md-7 ps-xl-8">
              <div id="courseStatisticsChart"></div>
            </div>
          </div>
          <div class="border rounded p-5 mt-5">
            <div class="row gap-4 gap-sm-0">
              <div class="col-12 col-sm-4">
                <div class="d-flex gap-2 align-items-center">
                  <div class="badge rounded bg-label-primary p-1"><i class="ti ti-book ti-sm"></i></div>
                  <h6 class="mb-0 fw-normal">Total Courses</h6>
                </div>
                <h4 class="my-2">142</h4>
              </div>
              <div class="col-12 col-sm-4">
                <div class="d-flex gap-2 align-items-center">
                  <div class="badge rounded bg-label-info p-1"><i class="ti ti-category ti-sm"></i></div>
                  <h6 class="mb-0 fw-normal">Categories</h6>
                </div>
                <h4 class="my-2">24</h4>
              </div>
              <div class="col-12 col-sm-4">
                <div class="d-flex gap-2 align-items-center">
                  <div class="badge rounded bg-label-danger p-1"><i class="ti ti-file-text ti-sm"></i></div>
                  <h6 class="mb-0 fw-normal">Subjects</h6>
                </div>
                <h4 class="my-2">586</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Course Statistics -->

    <!-- Student Support -->
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="mb-1">Student Support</h5>
            <p class="card-subtitle">Last 7 Days</p>
          </div>
          <div class="dropdown">
            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="supportTrackerMenu">
              <i class="ti ti-dots-vertical ti-md text-muted"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="{{ route('studentquery') }}">View Queries</a>
            </div>
          </div>
        </div>
        <div class="card-body row">
          <div class="col-12 col-sm-4 col-md-12 col-lg-4">
            <div class="mt-lg-4 mt-lg-2 mb-lg-6 mb-2">
              <h2 class="mb-0">86</h2>
              <p class="mb-0">Open Queries</p>
            </div>
            <ul class="p-0 m-0">
              <li class="d-flex gap-4 align-items-center mb-lg-3 pb-1">
                <div class="badge rounded bg-label-primary p-1_5"><i class="ti ti-help ti-md"></i></div>
                <div>
                  <h6 class="mb-0 text-nowrap">New Queries</h6>
                  <small class="text-muted">42</small>
                </div>
              </li>
              <li class="d-flex gap-4 align-items-center mb-lg-3 pb-1">
                <div class="badge rounded bg-label-info p-1_5"><i class="ti ti-checks ti-md"></i></div>
                <div>
                  <h6 class="mb-0 text-nowrap">Resolved</h6>
                  <small class="text-muted">28</small>
                </div>
              </li>
              <li class="d-flex gap-4 align-items-center pb-1">
                <div class="badge rounded bg-label-warning p-1_5"><i class="ti ti-clock ti-md"></i></div>
                <div>
                  <h6 class="mb-0 text-nowrap">Response Time</h6>
                  <small class="text-muted">4.8 Hours</small>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-12 col-sm-8 col-md-12 col-lg-8">
            <div id="supportTrackerChart"></div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Student Support -->

    <!-- Learning Materials -->
    <div class="col-xxl-4 col-md-6">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="mb-1">Learning Materials</h5>
            <p class="card-subtitle">Recent Uploads</p>
          </div>
          <div class="dropdown">
            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button">
              <i class="ti ti-dots-vertical ti-md text-muted"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="{{ route('subjectvideo') }}">Videos</a>
              <a class="dropdown-item" href="{{ route('subjectnote') }}">Notes</a>
              <a class="dropdown-item" href="{{ route('ebook') }}">E-books</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            <li class="d-flex align-items-center mb-4">
              <div class="avatar flex-shrink-0 me-4">
                <i class="ti ti-video rounded-circle fs-2 text-danger"></i>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">New Videos</h6>
                  <small class="text-body">This Week</small>
                </div>
                <div class="user-progress">
                  <h6 class="mb-0">48</h6>
                </div>
              </div>
            </li>
            <li class="d-flex align-items-center mb-4">
              <div class="avatar flex-shrink-0 me-4">
                <i class="ti ti-notes rounded-circle fs-2 text-primary"></i>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Study Notes</h6>
                  <small class="text-body">Updated Today</small>
                </div>
                <div class="user-progress">
                  <h6 class="mb-0">23</h6>
                </div>
              </div>
            </li>
            <li class="d-flex align-items-center">
              <div class="avatar flex-shrink-0 me-4">
                <i class="ti ti-book rounded-circle fs-2 text-success"></i>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">E-books</h6>
                  <small class="text-body">New Additions</small>
                </div>
                <div class="user-progress">
                  <h6 class="mb-0">15</h6>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!--/ Learning Materials -->

    <!-- Recent Enrollments -->
    <div class="col-xxl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="card-title mb-0">Recent Enrollments</h5>
          <a href="{{ route('student') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Student</th>
                  <th>Course</th>
                  <th>Enrollment Date</th>
                  <th>Progress</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>John Doe</td>
                  <td>Web Development</td>
                  <td>2023-08-15</td>
                  <td>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar" role="progressbar" style="width: 65%"></div>
                    </div>
                  </td>
                </tr>
                <!-- Add more rows as needed -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!--/ Recent Enrollments -->
</div>
@endsection
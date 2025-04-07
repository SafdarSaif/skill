@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Profile Section -->
            {{-- <div class="col-md-4">
                <div class="card shadow-lg text-center">
                    <div class="card-body">
                        <img src="{{ asset('assets/img/avatars/profile.webp') }}" class="rounded-circle mb-3" width="120"
                            height="120" alt="Profile Image">
                        <h4 class="fw-bold">{{ $student->name }}</h4>
                        <p class="text-muted">Enrollment No: {{ $student->enrollment_no }}</p>
                        <span class="badge {{ $student->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $student->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-4">
                <div class="card shadow-lg text-center">
                    <div class="card-body">
                        {{-- Profile Image --}}
                        <img src="{{ $student->image ? asset($student->image) : asset('assets/img/avatars/profile.webp') }}"
                             class="rounded-circle mb-3"
                             width="120"
                             height="120"
                             alt="{{ $student->name ?? 'Profile Image' }}">
            
                        {{-- Student Name --}}
                        <h4 class="fw-bold">{{ $student->name ?? 'N/A' }}</h4>
            
                        {{-- Enrollment Number --}}
                        <p class="text-muted">
                            Enrollment No: {{ $student->enrollment_no ?? 'N/A' }}
                        </p>
            
                        {{-- Status Badge --}}
                        <span class="badge {{ $student->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $student->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
            

            <!-- Details Section -->
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti ti-info-circle"></i> Student Details</h5>
                        <a href="{{ route('student') }}" class="btn btn-light btn-sm">
                            <i class="ti ti-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Personal Information -->
                        <h5 class="text-primary"><i class="ti ti-user"></i> Personal Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $student->name }}</p>
                                <p><strong>Email:</strong> {{ $student->email }}</p>
                                <p><strong>Phone:</strong> {{ $student->mobile }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date of Birth:</strong> {{ date('d M Y', strtotime($student->dob)) }}</p>
                                <p><strong>Course:</strong> {{ $student->course }}</p>
                            </div>
                        </div>

                        <!-- Parent Details -->
                        <h5 class="text-primary"><i class="ti ti-home"></i> Parent Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Father's Name:</strong> {{ $student->fathers_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Mother's Name:</strong> {{ $student->mothers_name }}</p>
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <h5 class="text-primary"><i class="ti ti-map"></i> Contact Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Address:</strong> {{ $student->address }}</p>
                                <p><strong>City:</strong> {{ $student->city }}, {{ $student->district }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>State:</strong> {{ $student->state }}</p>
                                <p><strong>Country:</strong> {{ $student->country }}</p>
                                <p><strong>Pincode:</strong> {{ $student->pincode }}</p>
                            </div>
                        </div>

                        <!-- Educational Details -->
                        <h5 class="text-primary"><i class="ti ti-book"></i> Educational Details</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Highest Qualification:</strong> {{ $student->heighest_qualification }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

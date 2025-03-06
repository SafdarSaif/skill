@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center text-primary">
                        <h3>Edit Student Query</h3>
                        <p class="text-muted">Modify the student query details below</p>
                    </div>

                    <div class="card-body">
                        <form id="edit-student-query-form" action="{{ route('studentquery.update', $studentquery->id) }}"
                            method="POST">
                            @csrf

                            <!-- Student Name -->
                            <div class="mb-3">
                                <label for="student_name" class="form-label">Student Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="student_name" id="student_name" class="form-control"
                                    value="{{ old('student_name', $studentquery->name) }}" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $studentquery->email) }}" required>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control"
                                    value="{{ old('phone', $studentquery->phone) }}" required pattern="\d{10}"
                                    title="Enter a valid 10-digit phone number">
                            </div>

                            <!-- Query -->
                            <div class="mb-3">
                                <label for="query" class="form-label">Query <span class="text-danger">*</span></label>
                                <textarea name="query" id="query" class="form-control" rows="4" required>{{ old('query', $studentquery->query) }}</textarea>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize form validation
            $("#edit-student-query-form").validate({
                rules: {
                    student_name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    query: {
                        required: true,
                        minlength: 10
                    }
                },
                messages: {
                    student_name: {
                        required: "Please enter student name",
                        minlength: "Name must be at least 3 characters long"
                    },
                    email: {
                        required: "Please enter an email",
                        email: "Enter a valid email address"
                    },
                    phone: {
                        required: "Please enter your phone number",
                        digits: "Enter a valid 10-digit phone number",
                        minlength: "Phone number must be 10 digits",
                        maxlength: "Phone number must be 10 digits"
                    },
                    query: {
                        required: "Please enter the query",
                        minlength: "Query must be at least 10 characters long"
                    }
                }
            });
        });
    </script>
@endsection

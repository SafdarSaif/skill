<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Student Course Enrollment</h3>
        <p class="text-muted">Fill in the Student details below</p>
    </div>

    <form id="studentcourse-form" action="{{ route('studentcourse.store') }}" method="POST" class="row g-3">
        @csrf

        <!-- Student ID -->
        <div class="col-md-6">
            <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
            <select name="student_id" id="student_id" class="form-select" required>
                <option value="">Select Student</option>
                @foreach ($student as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Course ID -->
        <div class="col-md-6">
            <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
            <select name="course_id" id="course_id" class="form-select" required>
                <option value="">Select Course</option>
                @foreach ($course as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Payment ID -->
        <div class="col-md-6">
            <label for="payment_id" class="form-label">Payment <span class="text-danger">*</span></label>
            <select name="payment_id" id="payment_id" class="form-select" required>
                <option value="">Select Payment</option>
                @foreach ($studentpayment as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<!-- jQuery Validation -->
<script>
    $(document).ready(function() {
        $("#studentcourse-form").validate({
            rules: {
                student_id: {
                    required: true,
                    number: true
                },
                course_id: {
                    required: true,
                    number: true
                },
                payment_id: {
                    required: true,
                    number: true
                }
            },
            messages: {
                student_id: {
                    required: "Please select a student",
                    number: "Enter a valid student ID"
                },
                course_id: {
                    required: "Please select a course",
                    number: "Enter a valid course ID"
                },
                payment_id: {
                    required: "Please select a payment option",
                    number: "Enter a valid payment ID"
                }
            },
            submitHandler: function(form) {
                $(':input[type="submit"]').prop('disabled', true);
                var formData = new FormData(form);
                formData.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#student-course-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        toastr.error(response.responseJSON.message);
                    }
                });
            }
        });
    });
</script>

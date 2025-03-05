<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Payment</h3>
        <p class="text-muted">Fill in the payment details below</p>
    </div>

    <form id="payment-form" action="{{ route('payment.store') }}" method="POST" class="row g-3">
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
        $("#payment-form").validate({
            rules: {
                student_id: {
                    required: true,
                    number: true
                },
                course_id: {
                    required: true,
                    number: true
                },
                transaction_id: {
                    required: true
                },
                payment_status: {
                    required: true
                }
            },
            messages: {
                student_id: {
                    required: "Please enter student ID",
                    number: "Enter a valid number"
                },
                course_id: {
                    required: "Please enter course ID",
                    number: "Enter a valid number"
                },
                transaction_id: {
                    required: "Please enter transaction ID"
                },
                payment_status: {
                    required: "Please select payment status"
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
                        if (response.status == 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#payment-table').DataTable().ajax.reload();
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

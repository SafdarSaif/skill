<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Student Query</h3>
        <p class="text-muted">Fill in the student query details below</p>
    </div>

    <form id="student-query-form" action="{{ route('studentquery.store') }}" method="POST" enctype="multipart/form-data"
        class="row g-3">
        @csrf



        <!-- Video Type-->
        <div class="col-md-6">
            <label for="video_id" class="form-label">Select Video<span class="text-danger">*</span></label>
            <select name="video_id" id="video_id" class="form-select" required>
                <option value="">Select Video</option>
                @foreach ($subjectvideo as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Student Type-->
        <div class="col-md-6">
            <label for="video_id" class="form-label">Select Student<span class="text-danger">*</span></label>
            <select name="student_id" id="student_id" class="form-select" required>
                <option value="">Select Video</option>
                @foreach ($student as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>


        <!-- Email -->
        <div class="col-md-12">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <!-- Phone Number -->
        <div class="col-md-12">
            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input type="text" name="phone" id="phone" class="form-control" required pattern="\d{10}"
                title="Enter a valid 10-digit phone number">
        </div>

        <!-- Query -->
        <div class="col-md-12">
            <label for="query" class="form-label">Query <span class="text-danger">*</span></label>
            <textarea name="query" id="query" class="form-control" rows="4" required></textarea>
        </div>
        <!-- Answer -->
        <div class="col-md-12">
            <label for="answer" class="form-label">Answer</label>
            <textarea name="answer" id="answer" class="form-control" rows="4"></textarea>
        </div>
        {{-- <!-- Attachment -->
        <div class="col-md-12">
            <label for="attachment" class="form-label">Attachment</label>
            <input type="file" name="attachment" id="attachment" class="form-control">
        </div> --}}
        <!-- Attachment -->
        <div class="col-md-12">
            <label for="attachment" class="form-label">Attachment</label>
            <input type="file" name="attachment[]" id="attachment" class="form-control" multiple>
        </div>


        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Initialize form validation
        $("#student-query-form").validate({
            rules: {
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
                            $('#student-query-table').DataTable().ajax.reload();
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

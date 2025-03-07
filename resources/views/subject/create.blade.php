<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Subject</h3>
        <p class="text-muted">Fill in the subject details below</p>
    </div>

    <form id="subject-form" action="{{ route('subject.store') }}" method="POST" class="row g-3">
        @csrf

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

        <!-- Subject Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>
        <!-- Image Upload -->
        <div class="col-md-12">
            <label for="image" class="form-label">Subject Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
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
        $("#subject-form").validate({
            rules: {
                course_id: {
                    required: true,
                    number: true
                },
                name: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                course_id: {
                    required: "Please select a course",
                    number: "Enter a valid course ID"
                },
                name: {
                    required: "Please enter a subject name",
                    minlength: "Subject name must be at least 3 characters long"
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
                            $('#subjects-table').DataTable().ajax.reload();
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

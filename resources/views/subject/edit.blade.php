<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Subject</h3>
        <p class="text-muted">Update the subject details below</p>
    </div>

    <form id="subject-edit-form" action="{{ route('subject.update', $subject->id) }}" method="POST" class="row g-3">
        @csrf


        <!-- Course ID -->
        <div class="col-md-6">
            <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
            <select name="course_id" id="course_id" class="form-select" required>
                <option value="">Select Course</option>
                @foreach ($course as $id => $name)
                    <option value="{{ $id }}" {{ $subject->course_id == $id ? 'selected' : '' }}>
                        {{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Subject Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $subject->name }}"
                required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $subject->description }}</textarea>
        </div>

         <!--  Image -->
         <div class="col-md-12">
            <label for="edit-image" class="form-label">Image</label>
            <input type="file" name="image" id="edit-image" class="form-control">
            <div class="mt-2">
                <img id="edit-image-preview" src="{{ asset($subject->image) }}" alt="News Image" class="img-thumbnail"
                    width="150">
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<!-- jQuery Validation for Edit Form -->
<script>
    $(document).ready(function() {
        $("#subject-edit-form").validate({
            rules: {
                course_id: {
                    required: true,
                    number: true
                },
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                }
            },
            messages: {
                course_id: {
                    required: "Please select a course"
                },
                name: {
                    required: "Please enter the subject name",
                    minlength: "Subject name must be at least 3 characters"
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

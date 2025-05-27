<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Ebook</h3>
        <p class="text-muted">Fill in the ebook details below</p>
    </div>

    <form id="ebook-form" action="{{ route('ebook.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Type -->
        <div class="col-md-4">
            <label for="type_id" class="form-label">Type</label>
            <select name="type_id" id="type_id" class="form-select">
                <option value="">Select Type</option>
                @foreach ($types as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Category -->
        <div class="col-md-4">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-select">
                <option value="">Select Category</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Course -->
        <div class="col-md-4">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" id="course_id" class="form-select">
                <option value="">Select Course</option>
                @foreach ($courses as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Subject -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                <!-- Will be populated dynamically -->
            </select>
        </div>


        <!-- Subject Selection -->
        {{-- <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div> --}}
        <!-- Ebook Title -->
        <div class="col-md-6">
            <label for="title" class="form-label">Ebook Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="title" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>

        <!-- Uploader -->
        <div class="col-md-6">
            <label for="user_id" class="form-label">Uploaded By <span class="text-danger">*</span></label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Select Uploaded By</option>
                @foreach ($users as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Upload Type -->
        <div class="col-md-6">
            <label for="upload_type" class="form-label">Upload Type <span class="text-danger">*</span></label>
            <select name="upload_type" id="upload_type" class="form-select" required>
                <option value="url">External Link</option>
                <option value="pdf">Upload File</option>
            </select>
        </div>

        <!-- External Link -->
        <div class="col-md-12" id="link_field">
            <label for="ebook_link" class="form-label">External Link <span class="text-danger">*</span></label>
            <input type="url" name="ebook_link" id="ebook_link" class="form-control"
                placeholder="Enter external link">
        </div>

        <!-- File Upload -->
        <div class="col-md-12" id="file_field" style="display: none;">
            <label for="ebook_file" class="form-label">Upload File <span class="text-danger">*</span></label>
            <input type="file" name="ebook_file" id="ebook_file" class="form-control"
                accept=".pdf,.doc,.docx,.ppt,.pptx">
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
        // When Type changes
        $('#type_id').change(function() {
            var type_id = $(this).val();
            $('#category_id').html('<option value="">Loading...</option>');
            $('#course_id').html('<option value="">Select Course</option>');
            $('#subject_id').html('<option value="">Select Subject</option>');

            if (type_id) {
                $.ajax({
                    url: '{{ route('getCategories') }}',
                    type: 'GET',
                    data: {
                        type_id: type_id
                    },
                    success: function(response) {
                        $('#category_id').html('<option value="">Select Category</option>');
                        $.each(response, function(id, name) {
                            $('#category_id').append('<option value="' + id + '">' +
                                name + '</option>');
                        });
                    }
                });
            }
        });

        // When Category changes
        $('#category_id').change(function() {
            var type_id = $('#type_id').val();
            var category_id = $(this).val();
            $('#course_id').html('<option value="">Loading...</option>');
            $('#subject_id').html('<option value="">Select Subject</option>');

            if (type_id && category_id) {
                $.ajax({
                    url: '{{ route('getCourses') }}', // Route to get courses by type+category
                    type: 'GET',
                    data: {
                        type_id: type_id,
                        category_id: category_id
                    },
                    success: function(response) {
                        $('#course_id').html('<option value="">Select Course</option>');
                        $.each(response, function(id, name) {
                            $('#course_id').append('<option value="' + id + '">' +
                                name + '</option>');
                        });
                    }
                });
            }
        });

        // When Course changes
        $('#course_id').change(function() {
            var course_id = $(this).val();
            $('#subject_id').html('<option value="">Loading...</option>');

            if (course_id) {
                $.ajax({
                    url: '{{ route('getSubjects') }}', // Route to get subjects by course
                    type: 'GET',
                    data: {
                        course_id: course_id
                    },
                    success: function(response) {
                        $('#subject_id').html('<option value="">Select Subject</option>');
                        $.each(response, function(id, name) {
                            $('#subject_id').append('<option value="' + id + '">' +
                                name + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#upload_type').change(function() {
            if ($(this).val() === 'url') {
                $('#link_field').show();
                $('#ebook_link').prop('required', true);
                $('#file_field').hide();
                $('#ebook_file').prop('required', false);
            } else {
                $('#file_field').show();
                $('#ebook_file').prop('required', true);
                $('#link_field').hide();
                $('#ebook_link').prop('required', false);
            }
        });

        $("#ebook-form").validate({
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
                            $('#ebooks-table').DataTable().ajax.reload();
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

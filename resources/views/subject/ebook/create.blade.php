<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Ebook</h3>
        <p class="text-muted">Fill in the ebook details below</p>
    </div>

    <form id="ebook-form" action="{{ route('ebook.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Subject Selection -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
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

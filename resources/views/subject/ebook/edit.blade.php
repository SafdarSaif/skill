<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Ebook</h3>
        <p class="text-muted">Update the ebook details below</p>
    </div>

    <form id="ebook-form" action="{{ route('ebook.update', $ebook->id) }}" method="POST" enctype="multipart/form-data"
        class="row g-3">
        @csrf
        <!-- Subject Selection -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                    <option value="{{ $id }}" {{ $ebook->subject_id == $id ? 'selected' : '' }}>
                        {{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Ebook Title -->
        <div class="col-md-6">
            <label for="title" class="form-label">Ebook Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="title" class="form-control"
                value="{{ old('name', $ebook->name) }}" required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $ebook->description) }}</textarea>
        </div>

        <!-- Uploader -->
        <div class="col-md-6">
            <label for="user_id" class="form-label">Uploaded By <span class="text-danger">*</span></label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Select Uploaded By</option>
                @foreach ($users as $id => $name)
                    <option value="{{ $id }}" {{ $ebook->user_id == $id ? 'selected' : '' }}>
                        {{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Upload Type -->
        <div class="col-md-6">
            <label for="upload_type" class="form-label">Upload Type <span class="text-danger">*</span></label>
            <select name="upload_type" id="upload_type" class="form-select" required>
                <option value="url" {{ $ebook->external_link ? 'selected' : '' }}>External Link</option>
                <option value="pdf" {{ $ebook->file_location ? 'selected' : '' }}>Upload File</option>
            </select>
        </div>

        <!-- External Link -->
        <div class="col-md-12" id="link_field"
            style="{{ $ebook->external_link ? 'display:block;' : 'display:none;' }}">
            <label for="ebook_link" class="form-label">External Link <span class="text-danger">*</span></label>
            <input type="url" name="ebook_link" id="ebook_link" class="form-control"
                placeholder="Enter external link" value="{{ old('ebook_link', $ebook->external_link) }}">
        </div>

        <!-- File Upload -->
        <div class="col-md-12" id="file_field"
            style="{{ $ebook->file_location ? 'display:block;' : 'display:none;' }}">
            <label for="ebook_file" class="form-label">Upload File <span class="text-danger">*</span></label>
            <input type="file" name="ebook_file" id="ebook_file" class="form-control"
                accept=".pdf,.doc,.docx,.ppt,.pptx">
            @if ($ebook->file_location)
                <p class="mt-2"><strong>Current File:</strong>
                    <a href="{{ asset($ebook->file_location) }}" target="_blank">View File</a>
                </p>
                <input type="hidden" name="existing_ebook_file" value="{{ $ebook->file_location }}">
            @endif
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        function toggleUploadFields() {
            let uploadType = $('#upload_type').val();
            if (uploadType === 'url') {
                $('#link_field').show();
                // $('#ebook_link').prop('required', true);
                $('#file_field').hide();
                $('#ebook_file').prop('required', false);
            } else {
                $('#file_field').show();
                // $('#ebook_file').prop('required', true);
                $('#link_field').hide();
                $('#ebook_link').prop('required', false);
            }
        }

        $('#upload_type').change(toggleUploadFields);
        toggleUploadFields();

        $("#ebook-form").validate({
            submitHandler: function(form) {
                $(':input[type="submit"]').prop('disabled', true);
                var formData = new FormData(form);
                formData.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
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

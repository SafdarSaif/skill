<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Subject Notes</h3>
        <p class="text-muted">Update the subject notes details below</p>
    </div>

    <form id="edit-subject-notes-form" action="{{ route('subjectnote.update', $note->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                    <option value="{{ $id }}" {{ $note->subject_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="name" class="form-label">Note Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $note->name }}" required>
        </div>

        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ $note->description }}</textarea>
        </div>

        <div class="col-md-6">
            <label for="user_id" class="form-label">Uploaded By <span class="text-danger">*</span></label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Select Uploaded By</option>
                @foreach ($users as $id => $name)
                    <option value="{{ $id }}" {{ $note->user_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="upload_type" class="form-label">Upload Type <span class="text-danger">*</span></label>
            <select name="upload_type" id="upload_type" class="form-select" required>
                <option value="url" {{ $note->upload_type == 'url' ? 'selected' : '' }}>External Link</option>
                <option value="pdf" {{ $note->upload_type == 'pdf' ? 'selected' : '' }}>Upload File</option>
            </select>
        </div>

        <div class="col-md-12" id="link_field" style="{{ $note->upload_type == 'pdf' ? 'display: none;' : '' }}">
            <label for="note_link" class="form-label">External Link <span class="text-danger">*</span></label>
            <input type="url" name="note_link" id="note_link" class="form-control" value="{{ $note->url }}" placeholder="Enter external link">
        </div>

        <div class="col-md-12" id="file_field" style="{{ $note->upload_type == 'url' ? 'display: none;' : '' }}">
            <label for="note_file" class="form-label">Upload File <span class="text-danger">*</span></label>
            <input type="file" name="note_file" id="note_file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx">
            @if($note->file_path)
                <p class="mt-2">Current File: <a href="{{ asset($note->file_path) }}" target="_blank">View File</a>
                </p>
            @endif
        </div>

        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#upload_type').change(function() {
            if ($(this).val() === 'url') {
                $('#link_field').show();
                $('#note_link').prop('required', true);
                $('#file_field').hide();
                $('#note_file').prop('required', false);
            } else {
                $('#file_field').show();
                $('#note_file').prop('required', true);
                $('#link_field').hide();
                $('#note_link').prop('required', false);
            }
        });

        $("#edit-subject-notes-form").validate({
            // rules: {
            //     subject_id: {
            //         required: true
            //     },
            //     name: {
            //         required: true,
            //         minlength: 3
            //     },
            //     user_id: {
            //         required: true
            //     },
            //     upload_type: {
            //         required: true
            //     },
            //     note_link: {
            //         required: function() {
            //             return $("#upload_type").val() === "url";
            //         },
            //         url: true
            //     },
            //     note_file: {
            //         required: function() {
            //             return $("#upload_type").val() === "pdf";
            //         },
            //         extension: "pdf|doc|docx|ppt|pptx"
            //     }
            // },
            // messages: {
            //     subject_id: "Please select a subject",
            //     name: {
            //         required: "Please enter a note name",
            //         minlength: "Name must be at least 3 characters long"
            //     },
            //     user_id: "Please select an uploader",
            //     upload_type: "Please select an upload type",
            //     note_link: {
            //         required: "Please enter an external link",
            //         url: "Please enter a valid URL"
            //     },
            //     note_file: {
            //         required: "Please upload a file",
            //         extension: "Allowed file types: pdf, doc, docx, ppt, pptx"
            //     }
            // },
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
                            $('#subject-notes-table').DataTable().ajax.reload();
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

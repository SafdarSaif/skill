<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Privacy Policy</h3>
        <p class="text-muted">Modify the Privacy Policy details below</p>
    </div>

    <form id="edit-privacy-form" action="{{ route('privacy.update', $privacy->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Privacy Policy Content -->
        <div class="col-md-12">
            <label for="edit-content" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea name="content" id="edit-content" class="form-control" rows="6" required>{{ $privacy->content }}</textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>


{{-- <script>
    $(document).ready(function () {
        $("#edit-privacy-form").validate({
            rules: {
                content: {
                    required: true,
                    minlength: 20
                }
            },
            messages: {
                content: {
                    required: "Please enter the Privacy Policy content",
                    minlength: "Content must be at least 20 characters long"
                }
            },
            submitHandler: function (form) {
                $(':input[type="submit"]').prop('disabled', true);

                var formData = new FormData(form);

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#privacy-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        $(':input[type="submit"]').prop('disabled', false);
                        toastr.error(xhr.responseJSON?.message || "An error occurred");
                    }
                });
            }
        });
    });
</script> --}}

<script>
    $(document).ready(function () {
        // Initialize CKEditor
        CKEDITOR.replace('edit-content');

        // jQuery Validation with CKEditor content sync
        $("#edit-privacy-form").validate({
            ignore: [],
            rules: {
                content: {
                    required: function (textarea) {
                        CKEDITOR.instances['edit-content'].updateElement(); // Sync content
                        return true;
                    },
                    minlength: 20
                }
            },
            messages: {
                content: {
                    required: "Please enter the Privacy Policy content",
                    minlength: "Content must be at least 20 characters long"
                }
            },
            submitHandler: function (form) {
                $(':input[type="submit"]').prop('disabled', true);

                // Sync CKEditor content before submitting
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }

                var formData = new FormData(form);

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#privacy-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        $(':input[type="submit"]').prop('disabled', false);
                        toastr.error(xhr.responseJSON?.message || "An error occurred");
                    }
                });
            }
        });
    });
</script>
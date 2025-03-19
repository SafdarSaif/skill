<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Privacy Policy</h3>
        <p class="text-muted">Fill in the Privacy Policy details below</p>
    </div>

    <form id="privacy-form" action="{{ route('privacy.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Privacy Policy Content -->
        <div class="col-md-12">
            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea name="content" id="content" class="form-control" rows="6" required></textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<!-- Include CKEditor -->
<script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        // Initialize CKEditor
        CKEDITOR.replace('content');

        $("#privacy-form").validate({
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
            submitHandler: function(form) {
                $(':input[type="submit"]').prop('disabled', true);

                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }

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
                            $('#privacy-table').DataTable().ajax.reload();
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

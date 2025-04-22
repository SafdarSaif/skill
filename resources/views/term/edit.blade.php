<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Terms & Conditions</h3>
        <p class="text-muted">Modify the Terms & Conditions details below</p>
    </div>

    <form id="edit-terms-form" action="{{ route('term.update', $term->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Terms & Conditions Content -->
        <div class="col-md-12">
            <label for="edit-content" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea name="content" id="edit-content" class="form-control" rows="6" required>{{ $term->content }}</textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>


<script>
    $(document).ready(function () {
        // Initialize CKEditor on edit textarea
        CKEDITOR.replace('edit-content');

        $("#edit-terms-form").validate({
            ignore: [], // validate hidden fields (like CKEditor)
            rules: {
                content: {
                    required: function () {
                        CKEDITOR.instances['edit-content'].updateElement();
                        return CKEDITOR.instances['edit-content'].getData().trim() === '';
                    }
                }
            },
            messages: {
                content: {
                    required: "Please enter the Terms & Conditions content"
                }
            },
            submitHandler: function (form) {
                $(':input[type="submit"]').prop('disabled', true);
                CKEDITOR.instances['edit-content'].updateElement(); 

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
                            $('#terms-table').DataTable().ajax.reload();
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

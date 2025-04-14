<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Terms & Conditions</h3>
        <p class="text-muted">Fill in the Terms & Conditions details below</p>
    </div>

    <form id="terms-form" action="{{ route('term.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Terms & Conditions Content -->
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


<script>
    $(document).ready(function() {
        $("#terms-form").validate({
            rules: {
                content: {
                    required: true,
                    minlength: 20
                }
            },
            messages: {
                content: {
                    required: "Please enter the Terms & Conditions content",
                    minlength: "Content must be at least 20 characters long"
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
                            $('#terms-table').DataTable().ajax.reload();
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

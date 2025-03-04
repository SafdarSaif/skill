<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Category</h3>
        <p class="text-muted">Fill in the category details below</p>
    </div>

    <form id="category-form" action="{{ route('category.store') }}" method="POST" class="row g-3">
        @csrf

        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="slug" class="form-label">Slug (Optional)</label>
            <input type="text" name="slug" id="slug" class="form-control">
        </div>

        <div class="col-md-12 mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
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
        $("#category-form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                slug: {
                    alphanumeric: true
                },
                description: {
                    maxlength: 500
                }
            },
            messages: {
                name: {
                    required: "Please enter the category name",
                    minlength: "Category name must be at least 2 characters long"
                },
                slug: {
                    alphanumeric: "Slug can only contain letters and numbers"
                },
                description: {
                    maxlength: "Description cannot exceed 500 characters"
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
                        if (response.status == 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#category-table').DataTable().ajax.reload();
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

        // Add alphanumeric validation rule
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\-]+$/.test(value);
        }, "Only letters, numbers, and hyphens are allowed");
    });
</script>

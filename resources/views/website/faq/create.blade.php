<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add News</h3>
        <p class="text-muted">Fill in the news details below</p>
    </div>

    <form id="news-form" action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- News Title -->
        <div class="col-md-12">
            <label for="title" class="form-label">News Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <!-- News Image -->
        <div class="col-md-12">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        
        <!-- News Content -->
        <div class="col-md-12">
            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea name="content" id="content" class="form-control" rows="4" required></textarea>
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

        $("#news-form").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 3
                },
                content: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                title: {
                    required: "Please enter a news title",
                    minlength: "Title must be at least 3 characters long"
                },
                content: {
                    required: "Please enter news content",
                    minlength: "Content must be at least 10 characters long"
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
                            $('#news-table').DataTable().ajax.reload();
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

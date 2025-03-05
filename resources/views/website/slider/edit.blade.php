<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit News</h3>
        <p class="text-muted">Update the news details below</p>
    </div>

    <form id="edit-news-form" action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data"
        class="row g-3">
        @csrf

        <!-- News Name -->
        <div class="col-md-12">
            <label for="edit-name" class="form-label">News Name <span class="text-danger">*</span></label>
            <input type="text" name="title" id="edit-name" class="form-control" value="{{ $news->name }}"
                required>
        </div>

        <!-- News Image -->
        <div class="col-md-12">
            <label for="edit-image" class="form-label">Image</label>
            <input type="file" name="image" id="edit-image" class="form-control">
            <div class="mt-2">
                <img id="edit-image-preview" src="{{ asset($news->image) }}" alt="News Image" class="img-thumbnail"
                    width="150">
            </div>
        </div>

        <!-- News Content -->
        <div class="col-md-12">
            <label for="edit-content" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea name="content" id="edit-content" class="form-control" rows="4" required>{{ $news->content }}</textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<!-- Include CKEditor -->
<script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        // Initialize CKEditor
        CKEDITOR.replace('edit-content');

        // Image Preview
        $("#edit-image").change(function(event) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $("#edit-image-preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        $("#edit-news-form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                content: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: "Please enter a news name",
                    minlength: "Name must be at least 3 characters long"
                },
                content: {
                    required: "Please enter news content",
                    minlength: "Content must be at least 10 characters long"
                }
            },
            submitHandler: function(form) {
                $(':input[type="submit"]').prop('disabled', true);

                // Update CKEditor content before submitting
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

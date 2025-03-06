<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit FAQ</h3>
        <p class="text-muted">Modify the FAQ details below</p>
    </div>

    <form id="faq-edit-form" action="{{ route('faq.update', $faq->id) }}" method="POST" enctype="multipart/form-data"
        class="row g-3">
        @csrf


        <!-- FAQ Question -->
        <div class="col-md-12">
            <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
            <input type="text" name="question" id="question" class="form-control" value="{{ $faq->question }}"
                required>
        </div>

        <!-- FAQ Answer -->
        <div class="col-md-12">
            <label for="answer" class="form-label">Answer <span class="text-danger">*</span></label>
            <textarea name="answer" id="content" class="form-control" rows="4" required>{{ $faq->answer }}</textarea>
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
        CKEDITOR.replace('content');

        $("#faq-edit-form").validate({
            rules: {
                question: {
                    required: true,
                    minlength: 5
                },
                answer: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                question: {
                    required: "Please enter a question",
                    minlength: "Question must be at least 5 characters long"
                },
                answer: {
                    required: "Please enter an answer",
                    minlength: "Answer must be at least 10 characters long"
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
                            $('#faq-table').DataTable().ajax.reload();
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

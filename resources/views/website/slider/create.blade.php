<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Slider</h3>
        <p class="text-muted">Fill in the slider details below</p>
    </div>

    <form id="slider-form" action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Slider Name -->
        <div class="col-md-12">
            <label for="name" class="form-label">Slider Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>

        <!-- Slider Image -->
        <div class="col-md-6">
            <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
        </div>

        <!-- Position -->
        <div class="col-md-6">
            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
            <input type="number" name="position" id="position" class="form-control" value="0" required>
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
        $("#slider-form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                image: {
                    required: true,
                    accept: "image/*"
                },
                position: {
                    required: true,
                    number: true
                }
            },
            messages: {
                name: {
                    required: "Please enter a slider name",
                    minlength: "Name must be at least 3 characters long"
                },
                image: {
                    required: "Please upload an image",
                    accept: "Only image files (JPG, JPEG, PNG, GIF, WebP) are allowed"
                },
                position: {
                    required: "Please specify the position",
                    number: "Position must be a valid number"
                }
            },
            submitHandler: function(form) {
                let submitButton = $(form).find(':submit');
                submitButton.prop('disabled', true);

                var formData = new FormData(form);
                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response); // Debugging: Check the response

                        submitButton.prop('disabled', false);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#slidertable').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message || "Unexpected error occurred.");
                        }
                    },
                    error: function(xhr) {
                        submitButton.prop('disabled', false);
                        let errorMessage = "Something went wrong!";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    }
                });
            }
        });
    });
</script>

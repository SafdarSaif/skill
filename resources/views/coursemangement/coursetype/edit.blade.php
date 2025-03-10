<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary" id="modal-title">Edit Course Type</h3>
        <p class="text-muted">Fill in the course type details below to update</p>
    </div>

    <form id="coursetype-form" action= "{{ route('coursetype.update', $courseType->id) }}" method="POST" class="row g-3">
        @csrf
       
        <!-- Course Type Name -->
        <div class="col-md-12">
            <label for="name" class="form-label">Course Type Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required
                value="{{ $courseType->name }}">
        </div>


        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<!-- jQuery Validation -->
<script>
    $(document).ready(function () {
        $("#coursetype-form").validate({
            rules: {
                name: { required: true }
            },
            messages: {
                name: { required: "Please enter course type name" }
            },
            submitHandler: function (form) {
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
                    success: function (response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        if (response.status == 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#coursetype-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        toastr.error(response.responseJSON.message);
                    }
                });
            }
        });
    });
</script>


<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Course</h3>
        <p class="text-muted">Fill in the course details below</p>
    </div>

    <form id="course-form" action="{{ route('course.store') }}" method="POST" class="row g-3">
        @csrf

        <!-- Category -->
        <div class="col-md-6">
            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">Select Category</option>
                @foreach ($category as $id =>$name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        
        <!-- Course Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">Course Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required placeholder="Enter course name">
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter course description"></textarea>
        </div>

        <!-- Price & Duration -->
        <div class="col-md-6">
            <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price" class="form-control" required min="0" step="0.01" placeholder="Enter price">
        </div>

        <div class="col-md-6">
            <label for="duration" class="form-label">Duration <span class="text-danger">*</span></label>
            <input type="text" name="duration" id="duration" class="form-control" required placeholder="e.g. 3 Months">
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
    $(document).ready(function () {
        $("#course-form").validate({
            rules: {
                name: { required: true },
                price: { required: true, number: true, min: 0 },
                duration: { required: true },
                category_id: { required: true }
            },
            messages: {
                name: { required: "Please enter course name" },
                price: { required: "Please enter price", number: "Enter a valid number", min: "Price cannot be negative" },
                duration: { required: "Please enter duration" },
                category_id: { required: "Please select a category" }
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
                            $('#course-table').DataTable().ajax.reload();
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

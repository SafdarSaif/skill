<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit payment</h3>
        <p class="text-muted">Update the payment details below</p>
    </div>

    <form id="edit-payment-form" action="{{ route('payment.update', $payment->id) }}" method="POST" class="row g-3">
        @csrf

        <!-- Hidden payment ID -->
        <input type="hidden" name="payment_id" value="{{ $payment->id }}">

        <!-- Category -->
        <div class="col-md-6">
            <label for="edit_category_id" class="form-label">Category <span class="text-danger">*</span></label>
            <select name="category_id" id="edit_category_id" class="form-select" required>
                <option value="">Select Category</option>
                @foreach ($category as $id => $name)
                    <option value="{{ $id }}" {{ $payment->category_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- payment Name -->
        <div class="col-md-6">
            <label for="edit_name" class="form-label">payment Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_name" class="form-control" required value="{{ $payment->name }}">
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="edit_description" class="form-label">Description</label>
            <textarea name="description" id="edit_description" class="form-control" rows="3">{{ $payment->description }}</textarea>
        </div>

        <!-- Price & Duration -->
        <div class="col-md-6">
            <label for="edit_price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
            <input type="number" name="price" id="edit_price" class="form-control" required min="0" step="0.01" value="{{ $payment->price }}">
        </div>

        <div class="col-md-6">
            <label for="edit_duration" class="form-label">Duration <span class="text-danger">*</span></label>
            <input type="text" name="duration" id="edit_duration" class="form-control" required value="{{ $payment->duration }}">
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<!-- jQuery AJAX Validation & Submission -->
<script>
    $(document).ready(function () {
        $("#edit-payment-form").validate({
            rules: {
                name: { required: true },
                price: { required: true, number: true, min: 0 },
                duration: { required: true },
                category_id: { required: true }
            },
            messages: {
                name: { required: "Please enter payment name" },
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
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#payment-table').DataTable().ajax.reload();
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

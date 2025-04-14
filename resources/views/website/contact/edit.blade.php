<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Contact</h3>
        <p class="text-muted">Update the contact details below</p>
    </div>

    <form id="contact-edit-form" action="{{ route('contact.update', $contact->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Name -->
        <div class="col-md-12">
            <label for="edit-name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit-name" class="form-control" value="{{ $contact->name }}" required>
        </div>

        <!-- Email -->
        <div class="col-md-12">
            <label for="edit-email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="edit-email" class="form-control" value="{{ $contact->email }}" required>
        </div>

        <!-- Phone -->
        <div class="col-md-12">
            <label for="edit-phone" class="form-label">Phone <span class="text-danger">*</span></label>
            <input type="tel" name="phone" id="edit-phone" class="form-control" value="{{ $contact->phone }}" required pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number">
        </div>

        <!-- Subject -->
        <div class="col-md-12">
            <label for="edit-subject" class="form-label">Subject <span class="text-danger">*</span></label>
            <input type="text" name="subject" id="edit-subject" class="form-control" value="{{ $contact->subject }}" required>
        </div>

        <!-- Message -->
        <div class="col-md-12">
            <label for="edit-message" class="form-label">Message <span class="text-danger">*</span></label>
            <textarea name="message" id="edit-message" class="form-control" rows="4" required>{{ $contact->message }}</textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update Contact</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $("#contact-edit-form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                subject: {
                    required: true,
                    minlength: 5
                },
                message: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: "Please enter a name (min 3 characters)",
                email: "Enter a valid email",
                phone: "Enter a valid 10-digit number",
                subject: "Subject must be at least 5 characters",
                message: "Message must be at least 10 characters"
            },
            submitHandler: function (form) {
                $(':input[type="submit"]').prop('disabled', true);
                var formData = new FormData(form);
                formData.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    url: $(form).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#faq-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        toastr.error(response.responseJSON.message || "Update failed.");
                    }
                });
            }
        });
    });
</script>

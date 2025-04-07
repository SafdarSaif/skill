<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Contact Us</h3>
        <p class="text-muted">Fill in the contact details below</p>
    </div>

    <form id="contact-form" action="{{ route('contact.store') }}" method="POST" enctype="multipart/form-data"
        class="row g-3">
        @csrf

        <!-- Name -->
        <div class="col-md-12">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <!-- Email -->
        <div class="col-md-12">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <!-- Phone -->
        <div class="col-md-12">
            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
            <input type="tel" name="phone" id="phone" class="form-control" required pattern="[0-9]{10}"
                title="Please enter a valid 10-digit phone number">
        </div>
        <!-- Subject -->
        <div class="col-md-12">
            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
            <input type="text" name="subject" id="subject" class="form-control" required>
        </div>

        <!-- Message -->
        <div class="col-md-12">
            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
            <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Send Message</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>



<script>
    $(document).ready(function() {
        $("#contact-form").validate({
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
                name: "Please enter your name (at least 3 characters)",
                email: "Please enter a valid email address",
                phone: "Please enter a valid 10-digit phone number",
                subject: "Please enter a subject (at least 5 characters)",
                message: "Please enter a message (at least 10 characters)"
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

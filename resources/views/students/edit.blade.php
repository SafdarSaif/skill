<div class="modal-body">
    <div class="text-center mb-4">
        <h3 class="mb-2">Edit Student</h3>
    </div>

    <form id="student-form" action="{{ route('student.update', $student->id) }}" method="POST" class="row g-3">
        @csrf
        {{-- @method('PUT') --}}

        <!-- Personal Information -->
        <div class="col-12">
            <div class="card p-3 shadow-sm">
                <h5 class="mb-3">Personal Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="name">Student Name</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name', $student->name) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="form-control"
                            value="{{ old('dob', $student->dob) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parent Details -->
        <div class="col-12">
            <div class="card p-3 shadow-sm">
                <h5 class="mb-3">Parent Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="fathers_name">Father's Name</label>
                        <input type="text" id="fathers_name" name="fathers_name" class="form-control"
                            value="{{ old('fathers_name', $student->fathers_name) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="mothers_name">Mother's Name</label>
                        <input type="text" id="mothers_name" name="mothers_name" class="form-control"
                            value="{{ old('mothers_name', $student->mothers_name) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="col-12">
            <div class="card p-3 shadow-sm">
                <h5 class="mb-3">Contact Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ old('email', $student->email) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="mobile">Mobile</label>
                        <input type="text" id="mobile" name="mobile" class="form-control"
                            value="{{ old('mobile', $student->mobile) }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="address">Address</label>
                        <textarea id="address" name="address" class="form-control" required>{{ old('address', $student->address) }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="state">State</label>
                        <input type="text" id="state" name="state" class="form-control"
                            value="{{ old('state', $student->state) }}" placeholder="Enter state" required>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="district">District</label>
                        <input type="text" id="district" name="district" class="form-control"
                            value="{{ old('district', $student->district) }}" placeholder="Enter district" required>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="city">City</label>
                        <input type="text" id="city" name="city" class="form-control"
                            value="{{ old('city', $student->city) }}" placeholder="Enter city" required>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="pincode">Pincode</label>
                        <input type="text" id="pincode" name="pincode" class="form-control"
                            value="{{ old('pincode', $student->pincode) }}" placeholder="Enter pincode" required>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="country">Country</label>
                        <input type="text" id="country" name="country" class="form-control"
                            value="{{ old('country', $student->country) }}" placeholder="Enter country" required>

                    </div>
                </div>
            </div>
        </div>

        <!-- Educational Details -->
        <div class="col-12">
            <div class="card p-3 shadow-sm">
                <h5 class="mb-3">Educational Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="heighest_qualification">Highest Qualification</label>
                        <input type="text" id="heighest_qualification" name="heighest_qualification"
                            class="form-control"
                            value="{{ old('heighest_qualification', $student->heighest_qualification) }}" required>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<script>
    $("#student-form").validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            dob: {
                required: true,
                date: true
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            fathers_name: {
                required: true
            },
            mothers_name: {
                required: true
            },
            address: {
                required: true
            },
            state: {
                required: true
            },
            district: {
                required: true
            },
            city: {
                required: true
            },
            pincode: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            },
            country: {
                required: true
            },
            heighest_qualification: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter student name"
            },
            email: {
                required: "Please enter email",
                email: "Enter a valid email"
            },
            dob: {
                required: "Select date of birth"
            },
            mobile: {
                required: "Enter mobile number",
                minlength: "Enter a valid 10-digit number"
            },
            fathers_name: {
                required: "Enter father's name"
            },
            mothers_name: {
                required: "Enter mother's name"
            },
            pincode: {
                required: "Enter pincode",
                minlength: "Enter a valid 6-digit pincode"
            },
        }
    });

    $("#student-form").submit(function(e) {
        e.preventDefault();
        if ($("#student-form").valid()) {
            $(':input[type="submit"]').prop('disabled', true);
            var formData = new FormData(this);
            formData.append("_token", "{{ csrf_token() }}");

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    $(':input[type="submit"]').prop('disabled', false);
                    if (response.status == 'success') {
                        toastr.success(response.message);
                        $(".modal").modal('hide');
                        $('#student-table').DataTable().ajax.reload();
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
</script>

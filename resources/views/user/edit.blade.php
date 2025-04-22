<div class="modal-header">
    <h5 class="modal-title">Edit User</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="userEditForm" action="{{ route('users.update', $user->id) }}" method="POST" autocomplete="off"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}"
                    oninput="createInitials()" autofocus>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-md-9">
                        <label class="form-label" for="avatar">Avatar</label>
                        <input type="file" id="avatar" name="avatar" class="form-control"
                            onchange="previewAvatar(this)" accept="image/*">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="avatar me-2">
                            <span id="nameInitials" class="avatar-initial rounded-circle bg-label-success"
                                style="display: none;">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            <img id="avatarImage" src="{{ asset($user->profile_photo_path) }}" alt="Avatar"
                                class="rounded" style="display: {{ $user->profile_photo_path ? 'block' : 'none' }};">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="mobile">Mobile</label>
                <input type="tel" id="mobile" name="mobile" class="form-control" value="{{ $user->mobile }}">
            </div>

            <div class="col-md-6">
                <label class="form-label" for="role_id">Role</label>
                <select class="form-select" id="role_id" name="role_id">
                    <option value="">Choose</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-6 form-password-toggle">
                <label class="form-label" for="password">New Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password"
                        placeholder="Leave blank to keep current password" />
                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarImage').attr('src', e.target.result).show();
                $('#nameInitials').hide();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(function() {
        $("#userEditForm").validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                role_id: {
                    required: true
                },
                password: {
                    minlength: 8
                }
            }
        });

        

        $("#role_id").select2({
            placeholder: 'Choose',
            dropdownParent: $('#modal-lg')
        });

        $("#userEditForm").submit(function(e) {
            e.preventDefault();
            if ($("#userEditForm").valid()) {
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
                            $('#users-datatable').DataTable().ajax.reload();
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

        $('.form-password-toggle i').click(function(e) {
            e.preventDefault();
            let input = $(this).closest('.form-password-toggle').find('input');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(this).removeClass('ti-eye-off').addClass('ti-eye');
            } else {
                input.attr('type', 'password');
                $(this).removeClass('ti-eye').addClass('ti-eye-off');
            }
        });
    });
</script>

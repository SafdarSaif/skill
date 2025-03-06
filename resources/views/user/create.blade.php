<div class="modal-header">
  <h5 class="modal-title">Add User</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="userAddForm" action="{{ route('users') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label" for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Name" oninput="createInitials()"
          autofocus>
      </div>
      <div class="col-md-6">
        <div class="row mb-2">
          <div class="col-md-9">
            <label class="form-label" for="avatar">Avatar</label>
            <input type="file" id="avatar" name="avatar" class="form-control"
              onchange="document.getElementById('avatarImage').src = window.URL.createObjectURL(this.files[0]); $('#avatarImage').css('display', 'flex'); $('#nameInitials').css('display', 'none')"
              accept="image/*">
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <div class="avatar me-2">
              <span id="nameInitials" class="avatar-initial rounded-circle bg-label-success">UN</span>
              <img id="avatarImage" src="" alt="" class="rounded" style="display: none">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" autocomplete="new-email" name="email" class="form-control" placeholder="Email">
      </div>
      <div class="col-md-6">
        <label class="form-label" for="mobile">Mobile</label>
        <input type="tel" id="mobile" autocomplete="off" name="mobile" class="form-control" placeholder="ex: 987654XXX">
      </div>
      <div class="col-md-6">
        <label class="form-label" for="role_id">Role</label>
        <select class="form-select" id="role_id" name="role_id">
          <option value="">Choose</option>
          @foreach ($roles as $role)
        <option value="{{ $role->id }}">{{ $role->name }}</option>
      @endforeach
        </select>
      </div>
      <div class="col-md-6 form-password-toggle">
        <label class="form-label" for="password">Password</label>
        <div class="input-group input-group-merge">
          <input type="password" autocomplete="new-password" id="password" class="form-control" name="password"
            placeholder="" aria-describedby="password" />
          <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
  </div>
</form>
<script>
  const toggler = document.querySelectorAll('.form-password-toggle i')
  if (typeof toggler !== 'undefined' && toggler !== null) {
    toggler.forEach(el => {
      el.addEventListener('click', e => {
        e.preventDefault()
        const formPasswordToggle = el.closest('.form-password-toggle')
        const formPasswordToggleIcon = formPasswordToggle.querySelector('i')
        const formPasswordToggleInput = formPasswordToggle.querySelector('input')

        if (formPasswordToggleInput.getAttribute('type') === 'text') {
          formPasswordToggleInput.setAttribute('type', 'password')
          formPasswordToggleIcon.classList.replace('ti-eye', 'ti-eye-off')
        } else if (formPasswordToggleInput.getAttribute('type') === 'password') {
          formPasswordToggleInput.setAttribute('type', 'text')
          formPasswordToggleIcon.classList.replace('ti-eye-off', 'ti-eye')
        }
      })
    })
  }
</script>

<script>
  $(function () {
    $("#userAddForm").validate({
      rules: {
        name: {
          required: true
        },
        email: {
          required: true,
          email: true
        },
        mobile: {
          required: true
        },
        role_id: {
          required: true
        },
        password: {
          required: true,
          minlength: 8
        }
      }
    });

    $("#role_id").select2({
      placeholder: 'Choose',
      dropdownParent: $('#modal-lg')
    });

    $("#userAddForm").submit(function (e) {
      e.preventDefault();
      if ($("#userAddForm").valid()) {
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
          success: function (response) {
            $(':input[type="submit"]').prop('disabled', false);
            if (response.status == 'success') {
              toastr.success(response.message);
              $(".modal").modal('hide');
              $('#users-table').DataTable().ajax.reload();
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
    })
  })

  function createInitials() {
    const name = $('#name').val();
    const initials = name.match(/\b\w/g) || [];
    $('#nameInitials').text(initials.join('').toUpperCase());
  }
</script>
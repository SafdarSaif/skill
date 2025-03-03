<div class="modal-body">
  <div class="text-center mb-4">
    <h3 class="role-title mb-2">Add New Role</h3>
    <p class="text-muted">Set role permissions</p>
  </div>
  <!-- Add role form -->
  <form id="addRoleForm" action="{{ route('users.roles') }}" method="POST" class="row g-3">
    <div class="col-12 mb-4">
      <label class="form-label" for="name">Role Name</label>
      <input type="text" id="name" name="name" class="form-control" placeholder="Enter a role name"
        tabindex="-1" />
    </div>
    <div class="col-12">
      <h5>Role Permissions</h5>
      <!-- Permission table -->
      <div class="table-responsive">
        <table class="table table-flush-spacing">
          <tbody>
            <tr>
              <td class="text-nowrap fw-medium">Administrator Access <i class="ti ti-info-circle"
                  data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i></td>
              <td>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="selectAll" />
                  <label class="form-check-label" for="selectAll">
                    Select All
                  </label>
                </div>
              </td>
            </tr>
            @foreach ($permissionData as $permissionName => $permissions)
              <tr>
                <td class="text-nowrap fw-medium">
                  {{ ucwords($permissionName) }}
                </td>
                <td>
                  <div class="d-flex">
                    @foreach ($permissions as $id => $name)
                      <div class="form-check me-3 me-lg-5">
                        <input class="form-check-input permission-checkboxes" name="permissions[]"
                          value="{{ $id }}" type="checkbox" id="permission-checkbox-{{ $id }}" />
                        <label class="form-check-label" for="permission-checkbox-{{ $id }}">
                          {{ $name }}
                        </label>
                      </div>
                    @endforeach
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- Permission table -->
    </div>
    <div class="col-12 text-center mt-4">
      <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
    </div>
  </form>
  <!--/ Add role form -->
</div>

<script>
  $(function() {
    $('#selectAll').click(function() {
      if ($(this).prop("checked")) {
        $(".permission-checkboxes").prop("checked", true);
      } else {
        $(".permission-checkboxes").prop("checked", false);
      }
    });

    $('.permission-checkboxes').click(function() {
      if ($(".permission-checkboxes").length == $(".permission-checkboxes:checked").length) {
        //if the length is same then untick
        $("#selectAll").prop("checked", true);
      } else {
        //vise versa
        $("#selectAll").prop("checked", false);
      }
    });
  })

  $("#addRoleForm").validate({
    rules: {
      name: {
        required: true
      }
    },
    messages: {
      name: {
        required: "Please enter role name"
      }
    }
  });

  $("#addRoleForm").submit(function(e) {
    e.preventDefault();
    if ($("#addRoleForm").valid()) {
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
            setTimeout(function() {
              window.location.reload();
            }, 2000)
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
  })
</script>

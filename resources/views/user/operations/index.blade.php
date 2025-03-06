@extends('layouts.main')

@section('content')

<script type="module">
  $(function () {
    var dataTablePermissions = $('#users-table'),
      dt_permission;
    // Users List datatable
    if (dataTablePermissions.length) {
      dt_permission = dataTablePermissions.DataTable({
        ajax: "{{ route('operations') }}",
        columns: [
          {
            data: 'name'
          },
          {
            data: 'email',
          },
          {
            data: 'code'
          },
          {
            data: ''
          },
          {
            data: ''
          },
          {
            data: ''
          },
       
        ],
        columnDefs: [
          {
            targets: 0,
            render: function (data, type, full, meta) {
              var $name = full['name'];
              var $image = full['profile_photo_path'];
              var $image = $image ? $image.replace("assets/", "") : '';
              // return '<span class="text-nowrap">' + $name + '</span>';
              if ($image) {
                var $output =
                  '<img src="/' + $image.replace("/assets", "") + '" alt="Photo" class="rounded-circle">';
              } else {
                var stateNum = Math.floor(Math.random() * 6);
                var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                var $state = states[stateNum],
                  $name = full['name'],
                  $initials = $name.match(/\b\w/g) || [];
                $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials +
                  '</span>';
              }
              var $row_output =
                '<div class="d-flex justify-content-start align-items-center user-name">' +
                '<div class="avatar-wrapper">' +
                '<div class="avatar me-3">' +
                $output +
                '</div>' +
                '</div>' +
                '<div class="d-flex flex-column">' +
                '<a href="" class="text-body text-truncate"><span class="fw-medium">' +
                $name +
                '</span></a>' +

                '</div>' +
                '</div>';
              return $row_output;
            }
          },
          {
            targets: 1,
            render: function (data, type, full, meta) {
              var $code = full['email'];
              return '<span class="text-nowrap">' + $code + '</span>';
            }
          },
          {
            targets: 2,
            orderable: false,
            render: function (data, type, full, meta) {
              return data;
            }
          },
          {
            targets: 3,
            orderable: false,
            render: function (data, type, full, meta) {
              var $mobile = full['mobile'];
              return '<div class="row" style="width:250px !important;"><div class="col-md-10 px-0"><input type="password" class="form-control" disabled="" style="border: 0ch;" value="' + full.mobile + '" id="myInput' + full.id + '"></div> <div class="col-md-2"><i class="fa fa-eye pt-2 cursor-pointer" onclick="showPassword(' + full.id + ')"></i></div></div>';
            }
          },
          {
            targets: 4,
            orderable: false,
            render: function (data, type, full, meta) {
              var $checkedStatus = full['status'] == 1 ? ' checked' : '';
              var $nameStatus = full['status'] == 1 ? 'Yes' : 'No';
              var isDisabled = 'onclick="updateActiveStatus(&#39;/users/operations/status/' + full['id'] + '&#39;, &#39;users&#39;)"';

              return `
              <label class="switch">
                <input type="checkbox" ${isDisabled}${$checkedStatus} class="switch-input">
                <span class="switch-toggle-slider">
                  <span class="switch-on"></span>
                  <span class="switch-off"></span>
                </span>
                <span class="switch-label">${$nameStatus}</span>
              </label>
            `;
            }
          },

          {
            // Actions
            targets: -1,
            searchable: false,
            title: 'Actions',
            orderable: false,
            render: function (data, type, full, meta) {
              return (
                '<span class="text-nowrap"><button class="btn btn-sm btn-icon me-2" onclick="allot(&#39;/users/operations/allot-university/' + full['id'] + '&#39, &#39;modal-md&#39;)"><i class="ti ti-plus"></i></button>'+
                '<button class="btn btn-sm btn-icon me-2" onclick="edit(&#39;/users/operations/edit/' + full['id'] + '&#39, &#39;modal-lg&#39;)"><i class="ti ti-edit"></i></button>' +
                '<button onclick="destry(&#39;/users/operations/destroy/' + full['id'] + '&#39, &#39;users-table&#39;)" class="btn btn-sm btn-icon delete-record"><i class="ti ti-trash"></i></button></span>'
              );
            }
          }
        ],

        //DEEEPAK
        aaSorting: false,

        "dom": '<"row"<"col-sm-12 col-md-6 d-flex justify-content-start"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
          '<"table-responsive"t>' +
          '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

        scrollCollapse: true,
        destroy: true,
        drawCallback: function (settings) {
          $('[data-toggle="tooltip"]').tooltip({
            template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
          });
        },
        //DEEPAK
        language: {
          sLengthMenu: 'Show _MENU_',
          search: 'Search',
          searchPlaceholder: 'Search..'
        },

      });
    }
  });
</script>
<div class="row">
  <div class="col-md-6">
    <h4 class="mb-4">Operations</h4>
  </div>
  <div class="col-md-6">
    <div class="dt-buttons btn-group flex-wrap" style="float:right">
      <button class="btn add-new btn-primary mb-3 mb-md-0 waves-effect waves-light" tabindex="0"
        aria-controls="users-table" type="button" onclick="add('{{ route('operations.create') }}', 'modal-lg')">
        <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i> Operation </span>
      </button>
    </div>
  </div>
</div>


<!-- Permission Table -->
<div class="card">
  <div class="card-datatable table-responsive">

    <table id="users-table" class="table border-top dt-fixedheader table dataTable dtr-column">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Code</th>
          <th>Password</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<!--/ Permission Table -->
@endsection
<script>
  function showPassword(id) {
    var x = document.getElementById("myInput".concat(id));
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
</script>
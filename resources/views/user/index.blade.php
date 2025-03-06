@extends('layouts.main')


@section('content')
<script type="module">
  var table = $("#users-datatable").DataTable({
    ajax: "{{ route('users') }}",
    columns: [
      // columns according to JSON
      {
        data: ''
      },
      {
        data: 'name'
      },
      {
        data: 'role'
      },
      {
        data: 'created_at'
      },
      {
        data: 'action'
      }
    ],
    columnDefs: [{
      // For Responsive
      className: 'control',
      searchable: false,
      orderable: false,
      responsivePriority: 2,
      targets: 0,
      render: function (data, type, full, meta) {
        return '';
      }
    },
    {
      // User full name and email
      targets: 1,
      responsivePriority: 4,
      render: function (data, type, full, meta) {
        var $name = full['name'],
          $email = full['email'],
          $image = full['avatar'];
        $image = $image ? $image.replace("assets/", "") : '';
        if ($image) {
          // For Avatar image
          var $output =
            '<img src="' + assetsPath + $image.replace("/assets", "") + '" alt="Avatar" class="rounded-circle">';
        } else {
          // For Avatar badge
          var stateNum = Math.floor(Math.random() * 6);
          var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
          var $state = states[stateNum],
            $name = full['name'],
            $initials = $name.match(/\b\w/g) || [];
          $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
          $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials +
            '</span>';
        }
        // Creates full output for row
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
          '<small class="text-muted">' +
          $email +
          '</small>' +
          '</div>' +
          '</div>';
        return $row_output;
      }
    },
    {
      // User Role
      targets: 2,
      render: function (data, type, full, meta) {
        var $role = JSON.parse(full['role']);
        return "<span class='text-truncate d-flex align-items-center'>" + $role[0] +
          '</span>';
      }
    },
    {
      targets: 3,
      render: function (data, type, full, meta) {
        var $status = full['created_at'];
        return '<span class="text-truncate">' + $status + '</span>'
      }
    },
    {
      // Actions
      targets: -1,
      title: 'Actions',
      searchable: false,
      orderable: false,
      render: function (data, type, full, meta) {
        var $role = JSON.parse(full['role']);
        var dropDown = $role[0] == 'Super Admin' ? '' : '<a href="/users/assign-verticals/' + full['id'] + '" class="dropdown-item">Assign Vertical</a><a href="javascript:;" onclick="assignReporting(' + full['id'] + ')" class="dropdown-item">Reporting</a>';

        return (
          '<div class="d-flex align-items-center">' +
          '<a href="javascript:;" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>' +
          '<a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>' +
          '<div class="dropdown-menu dropdown-menu-end m-0">' +
          dropDown +
          '<a href="javascript:;" class="dropdown-item">Change Password</a>' +
          '</div>' +
          '</div>'
        );
      }
    }
    ],
    aaSorting: false,
    dom: '<"row me-2"' +
      '<"col-md-2"<"me-3"l>>' +
      '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
      '>t' +
      '<"row mx-2"' +
      '<"col-sm-12 col-md-6"i>' +
      '<"col-sm-12 col-md-6"p>' +
      '>',
    language: {
      sLengthMenu: '_MENU_',
      search: '',
      searchPlaceholder: 'Search..'
    },
    // Buttons with Dropdown
    buttons: [{
      extend: 'collection',
      className: 'btn btn-label-secondary dropdown-toggle mx-3 waves-effect waves-light',
      text: '<i class="ti ti-screen-share me-1 ti-xs"></i>Export',
      buttons: [{
        extend: 'print',
        text: '<i class="ti ti-printer me-2" ></i>Print',
        className: 'dropdown-item',
        exportOptions: {
          columns: [1, 2, 3, 4],
          // prevent avatar to be print
          format: {
            body: function (inner, coldex, rowdex) {
              if (inner.length <= 0) return inner;
              var el = $.parseHTML(inner);
              var result = '';
              $.each(el, function (index, item) {
                if (item.classList !== undefined && item.classList.contains('user-name')) {
                  result = result + item.lastChild.firstChild.textContent;
                } else if (item.innerText === undefined) {
                  result = result + item.textContent;
                } else result = result + item.innerText;
              });
              return result;
            }
          }
        },
        customize: function (win) {
          //customize print view for dark
          $(win.document.body)
            .css('color', headingColor)
            .css('border-color', borderColor)
            .css('background-color', bodyBg);
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('color', 'inherit')
            .css('border-color', 'inherit')
            .css('background-color', 'inherit');
        }
      },
      {
        extend: 'csv',
        text: '<i class="ti ti-file-text me-2" ></i>Csv',
        className: 'dropdown-item',
        exportOptions: {
          columns: [1, 2, 3, 4],
          // prevent avatar to be display
          format: {
            body: function (inner, coldex, rowdex) {
              if (inner.length <= 0) return inner;
              var el = $.parseHTML(inner);
              var result = '';
              $.each(el, function (index, item) {
                if (item.classList !== undefined && item.classList.contains('user-name')) {
                  result = result + item.lastChild.firstChild.textContent;
                } else if (item.innerText === undefined) {
                  result = result + item.textContent;
                } else result = result + item.innerText;
              });
              return result;
            }
          }
        }
      },
      {
        extend: 'excel',
        text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
        className: 'dropdown-item',
        exportOptions: {
          columns: [1, 2, 3],
          // prevent avatar to be display
          format: {
            body: function (inner, coldex, rowdex) {
              if (inner.length <= 0) return inner;
              var el = $.parseHTML(inner);
              var result = '';
              $.each(el, function (index, item) {
                if (item.classList !== undefined && item.classList.contains('user-name')) {
                  result = result + item.lastChild.firstChild.textContent;
                } else if (item.innerText === undefined) {
                  result = result + item.textContent;
                } else result = result + item.innerText;
              });
              return result;
            }
          }
        }
      },
      {
        extend: 'copy',
        text: '<i class="ti ti-copy me-2" ></i>Copy',
        className: 'dropdown-item',
        exportOptions: {
          columns: [1, 2, 3],
          // prevent avatar to be display
          format: {
            body: function (inner, coldex, rowdex) {
              if (inner.length <= 0) return inner;
              var el = $.parseHTML(inner);
              var result = '';
              $.each(el, function (index, item) {
                if (item.classList !== undefined && item.classList.contains('user-name')) {
                  result = result + item.lastChild.firstChild.textContent;
                } else if (item.innerText === undefined) {
                  result = result + item.textContent;
                } else result = result + item.innerText;
              });
              return result;
            }
          }
        }
      }
      ]
    },
    {
      text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New User</span>',
      className: 'add-new btn btn-primary waves-effect waves-light',
      attr: {
        "onclick": "add('{{ route('users.create') }}', 'modal-lg')"
      }
    }
    ],
    // For responsive popup
    responsive: {
      details: {
        display: $.fn.dataTable.Responsive.display.modal({
          header: function (row) {
            var data = row.data();
            return 'Details of ' + data['name'];
          }
        }),
        type: 'column',
        renderer: function (api, rowIdx, columns) {
          var data = $.map(columns, function (col, i) {
            return col.name !== '' ?
              '<tr data-dt-row="' +
              col.rowIndex +
              '" data-dt-column="' +
              col.columnIndex +
              '">' +
              '<td>' +
              col.title +
              ':' +
              '</td> ' +
              '<td>' +
              col.data +
              '</td>' +
              '</tr>' :
              '';
          }).join('');

          return data ? $('<table class="table"/><tbody />').append(data) : false;
        }
      }
    },
    initComplete: function () {
      // Adding role filter once table initialized
      this.api()
        .columns(2)
        .every(function () {
          var column = this;
          var select = $(
            '<select id="UserRole" class="form-select text-capitalize"><option value=""> Select Role </option></select>'
          )
            .appendTo('.user_role')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });

          column
            .data()
            .unique()
            .sort()
            .each(function (d, j) {
              d = JSON.parse(d);
              select.append('<option value="' + d[0] + '">' + d[0] + '</option>');
            });
        });
    }
  });
</script>

<script>
  function assignReporting(id) {
    $.ajax({
      url: '/users/reporting/' + id,
      type: 'GET',
      success: function (response) {
        $("#modal-lg-content").html(response);
        $("#modal-lg").modal('show');
      }
    })
  }
</script>
<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Session</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2">21,459</h3>
              <p class="text-success mb-0">(+29%)</p>
            </div>
            <p class="mb-0">Total Users</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="ti ti-user ti-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Paid Users</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2">4,567</h3>
              <p class="text-success mb-0">(+18%)</p>
            </div>
            <p class="mb-0">Last week analytics </p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="ti ti-user-plus ti-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Active Users</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2">19,860</h3>
              <p class="text-danger mb-0">(-14%)</p>
            </div>
            <p class="mb-0">Last week analytics</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="ti ti-user-check ti-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Pending Users</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2">237</h3>
              <p class="text-success mb-0">(+42%)</p>
            </div>
            <p class="mb-0">Last week analytics</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="ti ti-user-exclamation ti-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Users List Table -->
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-3">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
      <div class="col-md-4 user_role"></div>
      <div class="col-md-4 user_plan"></div>
      <div class="col-md-4 user_status"></div>
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table id="users-datatable" class="table">
      <thead class="border-top">
        <tr>
          <th></th>
          <th>User</th>
          <th>Role</th>
          <th>Created On</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@endsection
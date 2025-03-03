@extends('layouts.main')

@section('content')

<script type="module">
  $(function () {
    var dataTablePermissions = $('#users-table'),
      dt_permission;
    // Users List datatable
    if (dataTablePermissions.length) {
      dt_permission = dataTablePermissions.DataTable({
        ajax: "{{ route('board-manager') }}",
        columns: [
          {
          data: 'name'
        },
        {
          data: 'code'
        },
        {
          data: 'email'
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
        {
          data: ''
        },
      
        ],
        columnDefs: [
        //   {
        //   // For Responsive
        //   className: 'control',
        //   orderable: false,
        //   searchable: false,
        //   responsivePriority: 2,
        //   targets: 0,
        //   render: function (data, type, full, meta) {
        //     return '';
        //   }
        // },
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
            var $code = full['code'];
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
            var $code = full['code'];
            return '<span class="text-nowrap">' + $code + '</span>';
          }
        },
        {
          targets: 5,
          orderable: false,
          render: function (data, type, full, meta) {
            var $code = full['code'];
            return '<span class="text-nowrap">' + $code + '</span>';
          }
        },
        {
          targets: 6,
          orderable: false,
          render: function (data, type, full, meta) {
            var $code = full['code'];
            return '<span class="text-nowrap">' + $code + '</span>';
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
              '<span class="text-nowrap"><button class="btn btn-sm btn-icon me-2" onclick="edit(&#39;users/permissions&#39;, &#39;' + data + '&#39, &#39;modal-md&#39;)"><i class="ti ti-edit"></i></button>' +
              '<button class="btn btn-sm btn-icon delete-record"><i class="ti ti-trash"></i></button></span>'
            );
          }
        }
        ],

        language: {
          sLengthMenu: 'Show _MENU_',
          search: 'Search',
          searchPlaceholder: 'Search..'
        },
        buttons: [{
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i> Board Manager',
          className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
          attr: {
            'onclick': "add('{{ route('board-manager.create') }}', 'modal-lg')"
          },
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
          }
        }],
       
       
        // For responsive popup
        // responsive: {
        //   details: {
        //     display: $.fn.dataTable.Responsive.display.modal({
        //       header: function (row) {
        //         var data = row.data();
        //         return 'Details of ' + data['name'];
        //       }
        //     }),
        //     type: 'column',
        //     renderer: function (api, rowIdx, columns) {
        //       var data = $.map(columns, function (col, i) {
        //         return col.title !==
        //           '' // ? Do not show row in modal popup if title is blank (for check box)
        //           ?
        //           '<tr data-dt-row="' +
        //           col.rowIndex +
        //           '" data-dt-column="' +
        //           col.columnIndex +
        //           '">' +
        //           '<td>' +
        //           col.title +
        //           ':' +
        //           '</td> ' +
        //           '<td>' +
        //           col.data +
        //           '</td>' +
        //           '</tr>' :
        //           '';
        //       }).join('');

        //       return data ? $('<table class="table"/><tbody />').append(data) : false;
        //     }
        //   }
        // }
      });
    }
  });
</script>
<h4 class="mb-4">Board Managers</h4>

<!-- Permission Table -->
<div class="card">
  <div class="card-datatable table-responsive">

    <table id="users-table" class="table border-top dt-fixedheader table dataTable dtr-column">
      <thead>
        <tr>
          <th>Name</th>
          <th>Code</th>
          <th>Email</th>
          <th>Password</th>
          <th>Allot</th>
          <th>Allot</th>
          <th>Allot</th>
          <th>Allot</th>


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
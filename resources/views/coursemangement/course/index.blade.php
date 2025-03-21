@extends('layouts.main')
@section('content')
    <script type="module">
        $(function() {
            var dataTablecourse = $('#course-table'),
                dt_permission;
            // Users List datatable
            if (dataTablecourse.length) {
                dt_permission = dataTablecourse.DataTable({
                    ajax: "{{ route('course') }}",
                    columns: [{
                            data: 'DT_RowIndex'
                        },
                        {
                            data: 'category'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'image'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'is_banner'
                        }, // New column added
                        {
                            data: ''
                        }
                    ],
                    columnDefs: [{
                            targets: 0,
                            render: function(data, type, full, meta) {
                                return data;
                            }
                        },
                        {
                            // Name
                            targets: 2,
                            render: function(data, type, full, meta) {
                                return '<span class="text-nowrap">' + full['name'] + '</span>';
                            }
                        },
                        {
                            // Image Column
                            targets: 3,
                            render: function(data, type, full, meta) {
                                var imageUrl = full['image'] ? full['image'] : 'default-image.jpg';
                                return '<img src="' + imageUrl +
                                    '" alt="Course Image" width="50" height="50" class="rounded">';
                            }
                        },
                        {
                            // Status
                            targets: 4,
                            render: function(data, type, full, meta) {
                                var $checkedStatus = full['status'] == 1 ? 'checked' : '';
                                var $nameStatus = full['status'] == 1 ? 'Yes' : 'No';
                                var isDisabled = 'onclick="updateActiveStatus(\'/course/status/' +
                                    full['id'] + '\', \'course-table\')"';
                                return '<label class="switch">' +
                                    '<input type="checkbox" ' + isDisabled + ' ' + $checkedStatus +
                                    ' class="switch-input">' +
                                    '<span class="switch-toggle-slider">' +
                                    '<span class="switch-on"><i class="ti ti-check"></i></span>' +
                                    '<span class="switch-off"><i class="ti ti-x"></i></span>' +
                                    '</span>' +
                                    '<span class="switch-label">' + $nameStatus + '</span>' +
                                    '</label>';
                            }
                        },
                        {
                            // Is Banner Column
                            targets: 5,
                            render: function(data, type, full, meta) {
                                var checked = full['is_banner'] == 1 ? 'checked' : '';
                                return '<label class="switch">' +
                                    '<input type="checkbox" onclick="updateActiveStatus(\'/course/banner-status/' +
                                    full['id'] + '\', \'course-table\')" ' + checked +
                                    ' class="switch-input">' +
                                    '<span class="switch-toggle-slider">' +
                                    '<span class="switch-on"><i class="ti ti-check"></i></span>' +
                                    '<span class="switch-off"><i class="ti ti-x"></i></span>' +
                                    '</span>' +
                                    '<span class="switch-label">' + (full['is_banner'] == 1 ?
                                        'Yes' : 'No') + '</span>' +
                                    '</label>';
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            searchable: false,
                            title: 'Actions',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<span class="text-nowrap">' +
                                    '<button class="btn btn-sm btn-icon me-2" onclick="edit(\'/course/edit/' +
                                    full['id'] +
                                    '\', \'modal-lg\')"><i class="ti ti-edit"></i></button>' +
                                    '<button class="btn btn-sm btn-icon delete-record" onclick="destry(\'/course/destroy/' +
                                    full['id'] +
                                    '\', \'course-table\')"><i class="ti ti-trash"></i></button>' +
                                    '</span>'
                                );
                            }
                        }
                    ],
                    aaSorting: false,
                    dom: '<"row mx-1"' +
                        '<"col-sm-12 col-md-3" l>' +
                        '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"<"me-3"f>B>>' +
                        '>t' +
                        '<"row mx-2"' +
                        '<"col-sm-12 col-md-6"i>' +
                        '<"col-sm-12 col-md-6"p>' +
                        '>',
                    language: {
                        sLengthMenu: 'Show _MENU_',
                        search: 'Search',
                        searchPlaceholder: 'Search..'
                    },
                    buttons: [{
                        text: 'Add course',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('course.create') }}', 'modal-lg')"
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }],
                    // For responsive popup
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + data['name'];
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== '' ? '<tr data-dt-row="' + col
                                        .rowIndex +
                                        '" data-dt-column="' + col.columnIndex + '">' +
                                        '<td>' + col.title + ':</td> ' +
                                        '<td>' + col.data + '</td>' +
                                        '</tr>' : '';
                                }).join('');

                                return data ? $('<table class="table"/><tbody />').append(data) : false;
                            }
                        }
                    }
                });
            }
        });
    </script>

    <h4 class="mb-4">Course List</h4>

    <!-- Course Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="course-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Course Category</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Is Banner</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ Course Table -->
@endsection

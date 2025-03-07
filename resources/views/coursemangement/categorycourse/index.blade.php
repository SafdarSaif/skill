@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTableCategory = $('#category-table'),
                dt_category;

            if (dataTableCategory.length) {
                dt_category = dataTableCategory.DataTable({
                    ajax: "{{ route('category') }}",
                    columns: [
                        { data: 'DT_RowIndex', title: 'No.' },
                        { data: 'name', title: 'Category Name' },
                        { data: 'image', title: 'Image' },
                        { data: 'status', title: 'Status' },
                        { data: '', title: 'Actions' }
                    ],
                    columnDefs: [
                        {
                            targets: 2, // Image column
                            render: function(data, type, full, meta) {
                                return data ? `<img src="${data}" alt="News Image" width="50" height="50">` : 'No Image';
                            }
                        },
                        {
                            targets: 3, // Status column
                            render: function(data, type, full, meta) {
                                var checked = full['status'] == 1 ? 'checked' : '';
                                var statusText = full['status'] == 1 ? 'Active' : 'Inactive';
                                var isDisabled = `onclick="updateActiveStatus('/category/status/${full['id']}', 'category-table')"`;

                                return `<label class="switch">
                                    <input type="checkbox" ${isDisabled} ${checked} class="switch-input">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"><i class="ti ti-check"></i></span>
                                        <span class="switch-off"><i class="ti ti-x"></i></span>
                                    </span>
                                    <span class="switch-label">${statusText}</span>
                                </label>`;
                            }
                        },
                        {
                            targets: -1, // Actions column
                            searchable: false,
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return `<span class="text-nowrap">
                                    <button class="btn btn-sm btn-icon me-2" onclick="edit('/category/edit/${full['id']}', 'modal-lg')">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon delete-record" onclick="destroy('/category/destroy/${full['id']}', 'category-table')">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </span>`;
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
                        searchPlaceholder: 'Search Categories...'
                    },
                    buttons: [{
                        text: 'Add Category',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('category.create') }}', 'modal-lg')"
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }],
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
                                    return col.title !== '' ? '<tr data-dt-row="' + col.rowIndex +
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

    <h4 class="mb-4"> Course Category</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="category-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Category Name</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

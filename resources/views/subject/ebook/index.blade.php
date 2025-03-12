@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTableEbooks = $('#ebooks-table'),
                dt_ebooks;

            if (dataTableEbooks.length) {
                dt_ebooks = dataTableEbooks.DataTable({
                    ajax: "{{ route('ebook') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            title: 'No.'
                        },
                        {
                            data: 'subject.name',
                            title: 'Subject Name'
                        },
                        {
                            data: 'name',
                            title: 'Ebook Name'
                        },
                        {
                            data: 'description',
                            title: 'Description'
                        },
                        {
                            data: 'upload_type',
                            title: 'Type'
                        },
                        {
                            data: 'file_location',
                            title: 'File/URL'
                        },
                        {
                            data: 'status',
                            title: 'Status'
                        },
                        {
                            data: '',
                            title: 'Actions'
                        },
                    ],
                    columnDefs: [{
                            targets: 5,
                            render: function(data, type, full, meta) {
                                return full['upload_type'] === 'pdf' ?
                                    `<a href="${full['file_location']}" target="_blank">Download</a>` :
                                    `<a href="${full['external_link']}" target="_blank">View</a>`;
                            }
                        },
                        {
                            targets: 6,
                            render: function(data, type, full, meta) {
                                var checkedStatus = full['status'] == 1 ? 'checked' : '';
                                var nameStatus = full['status'] == 1 ? 'Active' : 'Inactive';
                                return `<label class="switch">
                                    <input type="checkbox" ${checkedStatus} onclick="updateActiveStatus('/ebook/status/${full['id']}', 'ebooks-table')" class="switch-input">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"><i class="ti ti-check"></i></span>
                                        <span class="switch-off"><i class="ti ti-x"></i></span>
                                    </span>
                                    <span class="switch-label">${nameStatus}</span>
                                </label>`;
                            }
                        },
                        {
                            targets: -1,
                            searchable: false,
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return `<span class="text-nowrap">
                                    <button class="btn btn-sm btn-icon me-2" onclick="edit('/ebook/edit/${full['id']}', 'modal-lg')" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon me-2 delete-record" onclick="destry('/ebook/destroy/${full['id']}', 'ebooks-table')" title="Delete">
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
                        searchPlaceholder: 'Search..'
                    },
                    buttons: [{
                        text: 'Add Ebook',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('ebook.create') }}', 'modal-lg')"
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
                                    return 'Details of ' + data['title'];
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== '' ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                                        <td>${col.title}:</td>
                                        <td>${col.data}</td>
                                    </tr>` : '';
                                }).join('');
                                return data ? $('<table class="table"/><tbody />').append(data) : false;
                            }
                        }
                    }
                });
            }
        });
    </script>

    <h4 class="mb-4">Ebooks List</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="ebooks-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Subject Name</th>
                        <th>Ebook Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>File/URL</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTableTerms = $('#terms-table'),
                dt_terms;

            if (dataTableTerms.length) {
                dt_terms = dataTableTerms.DataTable({
                    ajax: "{{ route('term') }}",
                    columns: [
                        { data: 'DT_RowIndex', title: 'No.' },
                        { data: 'content', title: 'Content' },
                        { data: 'status', title: 'Status' },
                        { data: '', title: 'Actions' }
                    ],
                    columnDefs: [
                        {
                            targets: 1, // Content Column
                            render: function(data, type, full, meta) {
                                if (!data) return "";
                                let shortContent = data.length > 100 ? data.substring(0, 100) + '...' : data;
                                return `<span title="${data}" data-bs-toggle="tooltip">${shortContent}</span>`;
                            }
                        },
                        {
                            targets: 2, // Status Column
                            render: function(data, type, full, meta) {
                                var checked = full['status'] == 1 ? 'checked' : '';
                                var statusText = full['status'] == 1 ? 'Active' : 'Inactive';

                                return `
                                    <label class="switch">
                                        <input type="checkbox" ${checked} onclick="updateActiveStatus('/term/status/${full['id']}', 'terms-table')" class="switch-input">
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ti ti-check"></i></span>
                                            <span class="switch-off"><i class="ti ti-x"></i></span>
                                        </span>
                                        <span class="switch-label">${statusText}</span>
                                    </label>`;
                            }
                        },
                        {
                            targets: -1, // Actions Column
                            searchable: false,
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return `
                                    <span class="text-nowrap">
                                        <button class="btn btn-sm btn-icon me-2" onclick="edit('/term/edit/${full['id']}', 'modal-lg')">
                                            <i class="ti ti-edit"></i>
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
                        searchPlaceholder: 'Search Terms & Conditions...'
                    },
                    buttons: [{
                        text: 'Add Terms & Conditions',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('term.create') }}', 'modal-lg')"
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
                                    return 'Details of ' + data['content'];
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

                // Enable Bootstrap tooltips
                // $('body').tooltip({ selector: '[data-bs-toggle="tooltip"]' });
            }
        });
    </script>

    <h4 class="mb-4">Terms & Conditions</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="terms-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

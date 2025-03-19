@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTableProgress = $('#progress-table'),
                dt_progress;

            if (dataTableProgress.length) {
                dt_progress = dataTableProgress.DataTable({
                    ajax: "{{ route('studentprogress') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            title: 'No.'
                        },
                        {
                            data: 'student_name',
                            title: 'Student Name'
                        },
                        {
                            data: 'course',
                            title: 'Course Name'
                        },
                        
                        {
                            data: 'subject_name',
                            title: 'Subject Name'
                        },
                        
                        {
                            data: 'progress',
                            title: ' Student Progress'
                        }
                        // {
                        //     data: 'status',
                        //     title: 'Status'
                        // },
                        // {
                        //     data: '',
                        //     title: 'Actions'
                        // }
                    ],
                    columnDefs: [

                        {
                            targets: 4,
                            render: function(data, type, full, meta) {
                                data = parseFloat(data) || 0;

                                let progressClass = 'bg-danger';
                                if (data >= 75) progressClass = 'bg-success';
                                else if (data >= 40) progressClass = 'bg-warning';

                                return `
            <div class="progress" style="height: 20px;">
                <div class="progress-bar ${progressClass}" role="progressbar" 
                     style="width: ${data}%; transition: width 0.5s;" 
                     aria-valuenow="${data}" aria-valuemin="0" aria-valuemax="100">
                     ${data}%
                </div>
            </div>`;
                            }
                        },



                        // {
                        //     targets: 4, // Status Column
                        //     render: function(data, type, full, meta) {
                        //         var checked = full['status'] == 1 ? 'checked' : '';
                        //         var statusText = full['status'] == 1 ? 'Active' : 'Inactive';
                        //         return `
                        //             <label class="switch">
                        //                 <input type="checkbox" ${checked} onclick="updateActiveStatus('/studentprogress/status/${full['id']}', 'progress-table')" class="switch-input">
                        //                 <span class="switch-toggle-slider">
                        //                     <span class="switch-on"><i class="ti ti-check"></i></span>
                        //                     <span class="switch-off"><i class="ti ti-x"></i></span>
                        //                 </span>
                        //                 <span class="switch-label">${statusText}</span>
                        //             </label>`;
                        //     }
                        // },
                        // {
                        //     targets: -1, // Actions Column
                        //     searchable: false,
                        //     orderable: false,
                        //     render: function(data, type, full, meta) {
                        //         return `
                        //             <span class="text-nowrap">
                        //                 <button class="btn btn-sm btn-icon me-2" onclick="edit('/studentprogress/edit/${full['id']}', 'modal-lg')">
                        //                     <i class="ti ti-edit"></i>
                        //                 </button>
                        //                 <button class="btn btn-sm btn-icon delete-record" onclick="destry('/studentprogress/destroy/${full['id']}', 'progress-table')">
                        //                     <i class="ti ti-trash"></i>
                        //                 </button>
                        //             </span>`;
                        //     }
                        // }
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
                        searchPlaceholder: 'Search Student Progress...'
                    },
                    // buttons: [{
                    //     text: 'Add Student Progress',
                    //     className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                    //     attr: {
                    //         'onclick': "add('{{ route('studentprogress.create') }}', 'modal-lg')"
                    //     },
                    //     init: function(api, node, config) {
                    //         $(node).removeClass('btn-secondary');
                    //     }
                    // }],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + data['student_name'];
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
                $('body').tooltip({
                    selector: '[data-bs-toggle="tooltip"]'
                });
            }
        });
    </script>

    <h4 class="mb-4">Student Progress</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="progress-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Student Name</th>
                        <th>Course Name</th>
                        <th>Subject Name</th>
                        <th>Student Progress</th>
                        {{-- <th>Status</th>
                        <th>Actions</th> --}}
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

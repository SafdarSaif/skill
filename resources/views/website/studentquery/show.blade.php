@extends('layouts.main')

@section('content')
    {{-- <script type="module">
        $(function() {
            var dataTableQuery = $('#video-query-table'),
                dt_query;

            if (dataTableQuery.length) {
                dt_query = dataTableQuery.DataTable({
                    ajax: {
                        url: "{{ route('studentquery.show', ['id' => $student->id, 'student_id' => $student->id]) }}",
                        dataSrc: function(json) {
                            console.log(json); // Log the response to see the structure
                            return json.data;
                        }
                    },
                    columns: [{
                            data: 'id',
                            title: 'Query ID'
                        },
                        {
                            data: 'video_name',
                            title: 'Video Name'
                        },
                        {
                            data: 'phone',
                            title: 'Mobile Number'
                        },
                        {
                            data: 'email',
                            title: 'Email'
                        },
                        {
                            data: 'total_queries',
                            title: 'Number of Queries'
                        },
                        {
                            data: null,
                            title: 'Action',
                            searchable: false,
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return `
                                <button class="btn btn-sm btn-info" onclick="view('/videoquery/show/${full['id']}', 'modal-lg')">
                                    <i class="ti ti-eye"></i> View & Solve
                                </button>`;
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
                        searchPlaceholder: 'Search Queries...'
                    },

                });
            }
        });
    </script> --}}
    <script type="module">
        $(function() {
            var dataTableQuery = $('#video-query-table'),
                dt_query;

            if (dataTableQuery.length) {
                dt_query = dataTableQuery.DataTable({
                    ajax: {
                        url: "{{ route('studentquery.show', ['id' => $student->id, 'student_id' => $student->id]) }}",
                        dataSrc: function(json) {
                            console.log(json); // Log the response to see the structure
                            return json.data;
                        }
                    },
                    columns: [{
                            data: null,
                            title: 'No',
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'video_name',
                            title: 'Video Name'
                        },
                        // {
                        //     data: 'phone',
                        //     title: 'Mobile Number'
                        // },
                        // {
                        //     data: 'email',
                        //     title: 'Email'
                        // },
                        {
                            data: 'total_queries',
                            title: 'Number of Queries'
                        },
                        {
                            data: null,
                            title: 'Action',
                            searchable: false,
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return `
                                    <button class="btn btn-sm btn-info" onclick="edit('/videoquery/editquery/${full['id']}', 'modal-lg')">
                                        <i class="ti ti-eye"></i> View & Solve
                                    </button>`;
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
                        searchPlaceholder: 'Search Queries...'
                    },
                    buttons: [{
                        text: 'GetBack to Student Query',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        action: function(e, dt, node, config) {
                            window.location.href = "{{ route('studentquery') }}";
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }]


                });
            }
        });
    </script>


    <h4 class="mb-4">Video Queries</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="video-query-table" class="table border-top">
                <thead>
                    <tr>
                        <th>Query ID</th>
                        <th>Video Name</th>
                        {{-- <th>Mobile Number</th>
                        <th>Email</th> --}}
                        <th>Number of Queries</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

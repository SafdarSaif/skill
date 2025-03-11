@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTableVideos = $('#videos-table'),
                dt_videos;

            if (dataTableVideos.length) {
                dt_videos = dataTableVideos.DataTable({
                    ajax: "{{ route('subjectvideo') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            title: 'No.'
                        },
                        {
                            data: 'name',
                            title: 'Video Name'
                        },
                        {
                            data: 'subject.name',
                            title: 'Subject'
                        },
                        {
                            data: 'user.name',
                            title: 'Uploader'
                        },
                        {
                            data: 'upload_type',
                            title: 'Upload Type'
                        },
                        {
                            data: 'video_url',
                            title: 'Video'
                        },
                        {
                            data: '',
                            title: 'Actions'
                        }
                    ],
                    columnDefs: [
                        {
                            targets: 5,
                            render: function(data, type, full, meta) {
                                if (full['upload_type'] === 'youtube') {
                                    // return '<iframe width="200" height="100" src="' + full[
                                    //         'video_url'] +
                                    //     '" frameborder="0" allowfullscreen></iframe>';
                                    return '<a href="' + full['video_url'] + '" target="_blank">' + full['video_url'] + '</a>';

                                } else {
                                    return '<video width="200" controls><source src="' + full[
                                        'video_url'] + '" type="video/mp4"></video>';
                                }
                            }
                        },
                        {
                            targets: -1,
                            searchable: false,
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<span class="text-nowrap">' +
                                    '<button class="btn btn-sm btn-icon me-2" onclick="edit(\'/subjectvideo/edit/' +
                                    full['id'] + '\', \'modal-lg\')" title="Edit">' +
                                    '<i class="ti ti-edit"></i></button>' +
                                    '<button class="btn btn-sm btn-icon me-2 delete-record" onclick="destry(\'/subjectvideo/destroy/' +
                                    full['id'] + '\', \'videos-table\')" title="Delete">' +
                                    '<i class="ti ti-trash"></i></button>' +
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
                        text: 'Add Video',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('subjectvideo.create') }}', 'modal-lg')"
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

    <h4 class="mb-4">Subject Videos</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="videos-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Video Name</th>
                        <th>Subject</th>
                        <th>Uploader</th>
                        <th>Upload Type</th>
                        <th>Video</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

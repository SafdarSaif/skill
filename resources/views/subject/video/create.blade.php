<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Subject Video</h3>
        <p class="text-muted">Fill in the subject video details below</p>
    </div>

    <form id="subject-video-form" action="{{ route('subjectvideo.store') }}" method="POST" enctype="multipart/form-data"
        class="row g-3">
        @csrf


        <!-- Type -->
        <div class="col-md-4">
            <label for="type_id" class="form-label">Type</label>
            <select name="type_id" id="type_id" class="form-select">
                <option value="">Select Type</option>
                @foreach ($types as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Category -->
        <div class="col-md-4">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-select">
                <option value="">Select Category</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Course -->
        <div class="col-md-4">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" id="course_id" class="form-select">
                <option value="">Select Course</option>
                @foreach ($courses as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Subject -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                <!-- Will be populated dynamically -->
            </select>
        </div>

        {{-- <!-- Subject Selection -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div> --}}

        <!-- Video Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">Video Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>

        <!-- Duration -->
        <div class="col-md-6">
            <label for="duration" class="form-label">Duration (HH:MM:SS)</label>
            <input type="text" name="duration" id="duration" class="form-control"
                pattern="^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$" placeholder="HH:MM:SS"
                title="Enter duration in HH:MM:SS format" readonly>
        </div>

        <!-- Uploader -->
        {{-- <div class="col-md-6">
            <label for="user_id" class="form-label">Uploader <span class="text-danger">*</span></label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Select Uploader</option>
                @foreach ($users as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div> --}}

        <!-- Position -->
        <div class="col-md-6">
            <label for="position" class="form-label">Position</label>
            <select name="position" id="position" class="form-select">
                <option value="0" selected>General Videos (0)</option>
                <option value="1">Last Videos (1)</option>
            </select>
        </div>

        <!-- Upload Type -->
        <div class="col-md-6">
            <label for="upload_type" class="form-label">Upload Type <span class="text-danger">*</span></label>
            <select name="upload_type" id="upload_type" class="form-select" required>
                <option value="youtube">YouTube</option>
                <option value="local">Local</option>
            </select>
        </div>

        <!-- YouTube Video URL -->
        <div class="col-md-12" id="youtube_field">
            <label for="video_url" class="form-label">YouTube Video URL <span class="text-danger">*</span></label>
            <input type="url" name="video_url" id="video_url" class="form-control"
                placeholder="Enter YouTube embedded URL">
            <small class="text-muted">⚠ Only YouTube embedded URLs are allowed (e.g.,
                <code>https://www.youtube.com/embed/VIDEO_ID</code>).</small>
        </div>

        <!-- Local File Upload -->
        <div class="col-md-12" id="local_field" style="display: none;">
            <label for="video_file" class="form-label">Upload Video File <span class="text-danger">*</span></label>
            <input type="file" name="video_file" id="video_file" class="form-control" accept="video/*">
        </div>


        <!-- YouTube Video Preview and Duration Display -->
        <div class="col-md-12 mt-3" id="youtube-preview" style="display: none;">
            {{-- <label class="form-label">YouTube Preview:</label> --}}
            <div id="player" style="width:100%; max-width:640px; height:360px;"></div>
            {{-- <p class="mt-2" id="duration-display" class="text-muted">Video Duration: --:--</p> --}}
        </div>


        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Save Video</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>






<script>
    $(document).ready(function() {
        // When Type changes
        $('#type_id').change(function() {
            var type_id = $(this).val();
            $('#category_id').html('<option value="">Loading...</option>');
            $('#course_id').html('<option value="">Select Course</option>');
            $('#subject_id').html('<option value="">Select Subject</option>');

            if (type_id) {
                $.ajax({
                    url: '{{ route('getCategories') }}',
                    type: 'GET',
                    data: {
                        type_id: type_id
                    },
                    success: function(response) {
                        $('#category_id').html('<option value="">Select Category</option>');
                        $.each(response, function(id, name) {
                            $('#category_id').append('<option value="' + id + '">' +
                                name + '</option>');
                        });
                    }
                });
            }
        });

        // When Category changes
        $('#category_id').change(function() {
            var type_id = $('#type_id').val();
            var category_id = $(this).val();
            $('#course_id').html('<option value="">Loading...</option>');
            $('#subject_id').html('<option value="">Select Subject</option>');

            if (type_id && category_id) {
                $.ajax({
                    url: '{{ route('getCourses') }}', // Route to get courses by type+category
                    type: 'GET',
                    data: {
                        type_id: type_id,
                        category_id: category_id
                    },
                    success: function(response) {
                        $('#course_id').html('<option value="">Select Course</option>');
                        $.each(response, function(id, name) {
                            $('#course_id').append('<option value="' + id + '">' +
                                name + '</option>');
                        });
                    }
                });
            }
        });

        // When Course changes
        $('#course_id').change(function() {
            var course_id = $(this).val();
            $('#subject_id').html('<option value="">Loading...</option>');

            if (course_id) {
                $.ajax({
                    url: '{{ route('getSubjects') }}', // Route to get subjects by course
                    type: 'GET',
                    data: {
                        course_id: course_id
                    },
                    success: function(response) {
                        $('#subject_id').html('<option value="">Select Subject</option>');
                        $.each(response, function(id, name) {
                            $('#subject_id').append('<option value="' + id + '">' +
                                name + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>
<!-- jQuery Script for Dynamic Field Display -->

<script>
    $(document).ready(function() {
        // Initially hide YouTube and Local video fields
        toggleUploadFields();

        // Change event for Upload Type
        $('#upload_type').change(function() {
            toggleUploadFields();
        });

        function toggleUploadFields() {
            if ($('#upload_type').val() === 'youtube') {
                $('#youtube_field').show();
                $('#video_url').prop('required', true);
                $('#local_field').hide();
                $('#video_file').prop('required', false);
            } else {
                $('#local_field').show();
                $('#video_file').prop('required', true);
                $('#youtube_field').hide();
                $('#video_url').prop('required', false);
            }
        }

        // File input event for duration calculation
        $('#video_file').on('change', function(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.preload = 'metadata';

                video.onloadedmetadata = function() {
                    window.URL.revokeObjectURL(video.src);

                    const duration = video.duration; // in seconds
                    const hours = Math.floor(duration / 3600).toString().padStart(2, '0');
                    const minutes = Math.floor((duration % 3600) / 60).toString().padStart(2, '0');
                    const seconds = Math.floor(duration % 60).toString().padStart(2, '0');

                    const formatted = `${hours}:${minutes}:${seconds}`;
                    $('#duration').val(formatted);
                };

                video.src = URL.createObjectURL(file);
            }
        });

        // Form validation and AJAX submission
        $("#subject-video-form").validate({
            rules: {
                subject_id: {
                    required: true
                },
                name: {
                    required: true,
                    minlength: 3
                },
                upload_type: {
                    required: true
                },
                video_url: {
                    required: function() {
                        return $("#upload_type").val() === "youtube";
                    },
                    url: /^https:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+(\?[^#]+)?$/
                }
            },
            messages: {
                subject_id: {
                    required: "Please select a subject"
                },
                name: {
                    required: "Please enter a video name",
                    minlength: "Video name must be at least 3 characters long"
                },
                upload_type: {
                    required: "Please select an upload type"
                },
                video_url: {
                    required: "Please enter a YouTube video URL",
                    url: "Please enter a valid YouTube URL"
                }
            },
            submitHandler: function(form) {
                $(':input[type="submit"]').prop('disabled', true);
                var formData = new FormData(form);
                formData.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $(".modal").modal('hide');
                            $('#videos-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(response) {
                        $(':input[type="submit"]').prop('disabled', false);
                        toastr.error(response.responseJSON.message);
                    }
                });
            }
        });
    });
</script>


<!-- Load YouTube IFrame API -->
<script src="https://www.youtube.com/iframe_api"></script>

<script>
    let player;

    function onYouTubeIframeAPIReady() {
        // This function is triggered once the API script loads
    }

    function loadYouTubeVideo(videoId) {
        if (player) {
            player.loadVideoById(videoId);
        } else {
            player = new YT.Player('player', {
                height: '360',
                width: '640',
                videoId: videoId,
                events: {
                    'onReady': onPlayerReady
                }
            });
        }
    }

    // function onPlayerReady(event) {
    //     setTimeout(() => {
    //         const durationInSeconds = player.getDuration();
    //         const minutes = Math.floor(durationInSeconds / 60).toString().padStart(2, '0');
    //         const seconds = Math.floor(durationInSeconds % 60).toString().padStart(2, '0');
    //         const formatted = `00:${minutes}:${seconds}`;
    //         $('#duration-display').text(`Video Duration: ${minutes} min ${seconds} sec`);
    //         $('#duration').val(formatted);
    //     }, 1000);
    // }

    function onPlayerReady(event) {
        setTimeout(() => {
            const durationInSeconds = player.getDuration();

            const hours = Math.floor(durationInSeconds / 3600).toString().padStart(2, '0');
            const minutes = Math.floor((durationInSeconds % 3600) / 60).toString().padStart(2, '0');
            const seconds = Math.floor(durationInSeconds % 60).toString().padStart(2, '0');

            const formatted = `${hours}:${minutes}:${seconds}`;
            $('#duration-display').text(`Video Duration: ${hours} hr ${minutes} min ${seconds} sec`);
            $('#duration').val(formatted);
        }, 1000);
    }



    // Watch YouTube URL input
    $('#video_url').on('input', function() {
        const url = $(this).val();
        const match = url.match(/\/embed\/([a-zA-Z0-9_-]{11})/);
        if (match && match[1]) {
            const videoId = match[1];
            $('#youtube-preview').show();
            loadYouTubeVideo(videoId);
        } else {
            $('#youtube-preview').hide();
            $('#duration-display').text('Video Duration: --:--');
            $('#duration').val('');
        }
    });

    //for both youtube and normal video URLs and embeded YouTube URLs
    // $('#video_url').on('input', function() {
    //     const url = $(this).val();

    //     // Match various YouTube URL formats
    //     const match = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);

    //     if (match && match[1]) {
    //         const videoId = match[1];
    //         $('#youtube-preview').show();
    //         loadYouTubeVideo(videoId); // Your custom function to load the video
    //     } else {
    //         $('#youtube-preview').hide();
    //         $('#duration-display').text('Video Duration: --:--');
    //         $('#duration').val('');
    //     }
    // });
</script>

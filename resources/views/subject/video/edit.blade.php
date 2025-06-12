<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Subject Video</h3>
        <p class="text-muted">Update the subject video details below</p>
    </div>

    <form id="subject-video-form" action="{{ route('subjectvideo.update', $video->id) }}" method="POST"
        enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Type -->
        <div class="col-md-4">
            <label for="type_id" class="form-label">Type</label>
            <select name="type_id" id="type_id" class="form-select">
                <option value="">Select Type</option>
                @foreach ($types as $id => $name)
                <option value="{{ $id }}" {{ old('type_id', $video->type_id) == $id ? 'selected' : '' }}>{{ $name }}
                </option>
                @endforeach
            </select>

        </div>

        <!-- Category -->
        <div class="col-md-4">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-select">
                <option value="">Select Category</option>
                @foreach ($categories as $id => $name)
                <option value="{{ $id }}" {{ old('category_id', $video->category_id) == $id ? 'selected' : '' }}>{{
                    $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Course -->
        <div class="col-md-4">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" id="course_id" class="form-select">
                <option value="">Select Course</option>
                @foreach ($courses as $id => $name)
                <option value="{{ $id }}" {{ old('course_id', $video->course_id) == $id ? 'selected' : '' }}>{{ $name }}
                </option>
                @endforeach
            </select>

        </div>

        <!-- Subject -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                <option value="{{ $id }}" {{ old('subject_id', $video->subject_id) == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
                @endforeach
            </select>

        </div>

        <!-- Subject Selection -->
        {{-- <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                <option value="{{ $id }}" {{ old('subject_id', $video->subject_id) == $id ? 'selected' : '' }}>
                    {{ $name }}</option>
                @endforeach
            </select>
        </div> --}}

        <!-- Video Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">Video Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $video->name) }}"
                required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"
                rows="3">{{ old('description', $video->description) }}</textarea>
        </div>

        <!-- Duration (readonly) -->
        <div class="col-md-6">
            <label for="duration" class="form-label">Duration (HH:MM:SS)</label>
            <input type="text" name="duration" id="duration" class="form-control"
                value="{{ old('duration', gmdate('H:i:s', $video->duration)) }}"
                pattern="^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$" placeholder="HH:MM:SS" readonly>
        </div>

        <!-- Uploader -->
        <div class="col-md-6">
            <label for="user_id" class="form-label">Uploader <span class="text-danger">*</span></label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Select Uploader</option>
                @foreach ($users as $id => $name)
                <option value="{{ $id }}" {{ old('user_id', $video->user_id) == $id ? 'selected' : '' }}>
                    {{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Position -->
        <div class="col-md-6">
            <label for="position" class="form-label">Position</label>
            <select name="position" id="position" class="form-select">
                <option value="0" {{ old('position', $video->position) == 0 ? 'selected' : '' }}>General Videos (0)
                </option>
                <option value="1" {{ old('position', $video->position) == 1 ? 'selected' : '' }}>Last Videos (1)
                </option>
            </select>
        </div>

        <!-- Upload Type -->
        <div class="col-md-6">
            <label for="upload_type" class="form-label">Upload Type <span class="text-danger">*</span></label>
            <select name="upload_type" id="upload_type" class="form-select" required>
                <option value="youtube" {{ old('upload_type', $video->upload_type) == 'youtube' ? 'selected' : ''
                    }}>YouTube</option>
                <option value="local" {{ old('upload_type', $video->upload_type) == 'local' ? 'selected' : '' }}>Local
                </option>
                <option value="drive_link" {{ old('upload_type', $video->upload_type) == 'drive_link' ? 'selected' : ''
                    }}>Google Drive Link</option>
            </select>
        </div>

        <!-- YouTube Video URL -->
        <div class="col-md-12" id="youtube_field"
            style="{{ old('upload_type', $video->upload_type) == 'youtube' ? '' : 'display: none;' }}">
            <label for="video_url" class="form-label">YouTube Video URL <span class="text-danger">*</span></label>
            <input type="url" name="video_url" id="video_url" class="form-control"
                value="{{ $video->upload_type == 'youtube' ? $video->video_url : '' }}" placeholder="Enter YouTube URL">
            <!--<small class="text-muted">⚠ Only YouTube embedded URLs are allowed (e.g., <code>https://www.youtube.com/embed/VIDEO_ID</code>).</small>-->
        </div>

        <!-- Local File Upload -->
        <div class="col-md-12" id="local_field"
            style="{{ old('upload_type', $video->upload_type) == 'local' ? '' : 'display: none;' }}">
            <label for="video_file" class="form-label">Upload Video File <span class="text-danger">*</span></label>
            <input type="file" name="video_file" id="video_file" class="form-control" accept="video/*">
            <progress id="uploadProgressBar" value="0" max="100" style="width: 100%; display: none;"></progress>

            @if ($video->upload_type == 'local' && $video->video_url)
            <p class="mt-2"><strong>Current File:</strong> {{ basename($video->video_url) }}</p>
            <video width="100%" height="auto" controls class="mt-2">
                <source src="{{ asset($video->video_url) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            @endif
        </div>

        <!-- Google Drive Video URL -->
        <div class="col-md-12" id="drive_field">
            <label for="drive_link" class="form-label">Google Drive URL <span class="text-danger">*</span></label>
            <input type="url" name="drive_link" id="drive_link" class="form-control"
                value="{{ $video->upload_type == 'drive_link' ? $video->video_url : '' }}"
                placeholder="Enter Google Drive URL">
            <!--<small class="text-muted">⚠ Only YouTube embedded URLs are allowed (e.g.,-->
            <!--    <code>https://www.youtube.com/embed/VIDEO_ID</code>).</small>-->
        </div>
        <!-- YouTube Video Preview and Duration Display -->
        <div class="col-md-12 mt-3" id="youtube-preview" style="display: none;">
            {{-- <label class="form-label">YouTube Preview:</label> --}}
            <div id="player" style="width:100%; max-width:640px; height:360px;"></div>
            {{-- <p class="mt-2" id="duration-display" class="text-muted">Video Duration: --:--</p> --}}
        </div>
        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update Video</button>
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
                    url: '{{ route('getSubjects') }}',
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

<!-- jQuery Script -->
<script>
    $(document).ready(function () {

        // function toggleFields() {
        //     const uploadType = $('#upload_type').val();
        //     if ($('#upload_type').val() === 'youtube') {
        //         $('#youtube_field').show();
        //         $('#video_url').prop('required', true);
        //         $('#local_field').hide();
        //         $('#video_file').prop('required', false);
        //         $('#drive_field').hide();
        //         $('#drive_link').prop('required', false);
        //     }
        //     else if($('#upload_type').val() == 'drive_link'){
        //         $('#drive_field').show();
        //         $('#drive_link').prop('required', true);
        //         $('#local_field').hide();
        //         $('#video_file').prop('required', false);
        //         $('#youtube_field').hide();
        //         $('#video_url').prop('required', false);
        //     }
        //     else {
        //         $('#local_field').show();
        //         $('#video_file').prop('required', true);
        //         $('#youtube_field').hide();
        //         $('#video_url').prop('required', false);
        //         $('#drive_field').hide();
        //         $('#drive_link').prop('required', false);
        //     }
        // }

        function toggleFields() {
    const uploadType = $('#upload_type').val();

    if (uploadType === 'youtube') {
        $('#youtube_field').show();
        $('#video_url').prop('required', true);
        $('#local_field').hide();
        $('#video_file').prop('required', false);
        $('#drive_field').hide();
        $('#drive_link').prop('required', false);
        $('#duration_field').hide(); // Hide duration for YouTube
        $('#duration').prop('readonly', true);
    } else if (uploadType === 'drive_link') {
        $('#drive_field').show();
        $('#drive_link').prop('required', true);
        $('#local_field').hide();
        $('#video_file').prop('required', false);
        $('#youtube_field').hide();
        $('#video_url').prop('required', false);
        $('#duration_field').show(); // Show duration for Drive
        $('#duration').prop('readonly', false); // Allow manual entry
    } else {
        $('#local_field').show();
        $('#video_file').prop('required', true);
        $('#youtube_field').hide();
        $('#video_url').prop('required', false);
        $('#drive_field').hide();
        $('#drive_link').prop('required', false);
        $('#duration_field').hide(); // Hide duration for local
        $('#duration').prop('readonly', true);
    }
}

        toggleFields();
        $('#upload_type').change(toggleFields);

        $('#video_file').on('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.preload = 'metadata';
                video.onloadedmetadata = function () {
                    window.URL.revokeObjectURL(video.src);
                    const duration = video.duration;
                    const hours = Math.floor(duration / 3600).toString().padStart(2, '0');
                    const minutes = Math.floor((duration % 3600) / 60).toString().padStart(2, '0');
                    const seconds = Math.floor(duration % 60).toString().padStart(2, '0');
                    $('#duration').val(`${hours}:${minutes}:${seconds}`);
                };
                video.src = URL.createObjectURL(file);
            }
        });

        // Validate and AJAX Submit
        // $('#subject-video-form').validate({
        //     rules: {
        //         subject_id: { required: true },
        //         name: { required: true, minlength: 3 },
        //         user_id: { required: true },
        //         upload_type: { required: true },
        //           duration: {
        //             required: function() {
        //                 return $("#upload_type").val() === "drive_link";
        //             },
        //             pattern: {
        //                 param: /^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$/,
        //                 depends: function() {
        //                     // Only validate pattern if value is not empty (to avoid failing when optional)
        //                     return $("#upload_type").val() === "drive_link" || $("#duration").val()
        //                         .trim() !== "";
        //                 }
        //             }
        //         }
        //     },
        //     messages: {
        //         subject_id: { required: "Please select a subject" },
        //         name: { required: "Enter a video name", minlength: "At least 3 characters" },
        //         user_id: { required: "Please select an uploader" },
        //         upload_type: { required: "Please select an upload type" },
        //          duration: {
        //             required: "Please enter video duration for Google Drive videos",
        //             pattern: "Duration must be in HH:MM:SS format"
        //         }
        //     },
        //     submitHandler: function (form) {
        //         const formData = new FormData(form);
        //         formData.append('_token', "{{ csrf_token() }}");

        //         // Reset and show progress bar
        //         $("#uploadProgressBar").val(0).show();

        //         $.ajax({
        //             url: $(form).attr('action'),
        //             method: $(form).attr('method'),
        //             data: formData,
        //             contentType: false,
        //             processData: false,

        //             // ✅ Upload progress tracking
        //             xhr: function () {
        //                 let xhr = new XMLHttpRequest();
        //                 xhr.upload.addEventListener("progress", function (e) {
        //                     if (e.lengthComputable) {
        //                         let percent = Math.round((e.loaded / e.total) * 100);
        //                         $("#uploadProgressBar").val(percent);
        //                     }
        //                 }, false);
        //                 return xhr;
        //             },

        //             beforeSend: () => {
        //                 $(':input[type="submit"]').prop('disabled', true);
        //             },

        //             success: function (response) {
        //                 toastr.success(response.message);
        //                 $(".modal").modal('hide');
        //                 $('#subjects-video-table').DataTable().ajax.reload();
        //             },

        //             error: function (response) {
        //                 toastr.error(response.responseJSON?.message || 'Something went wrong.');
        //             },

        //             complete: () => {
        //                 $(':input[type="submit"]').prop('disabled', false);
        //                 $("#uploadProgressBar").hide(); // ✅ Hide progress bar after complete
        //             }
        //         });
        //     }
        // });

        // Custom HH:MM:SS duration format validation method
$.validator.addMethod("driveLink", function(value, element) {
    const drivePattern1 = /^https:\/\/drive\.google\.com\/file\/d\/[a-zA-Z0-9_-]{10,}\/view(\?.*)?$/;
    const drivePattern2 = /^https:\/\/drive\.google\.com\/uc\?export=download&id=[a-zA-Z0-9_-]{10,}$/;
    return this.optional(element) || drivePattern1.test(value) || drivePattern2.test(value);
}, "Please enter a valid Google Drive link.");

$.validator.addMethod("hhmmss", function(value, element) {
    return this.optional(element) || /^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$/.test(value);
}, "Duration must be in HH:MM:SS format");

$('#subject-video-form').validate({
    rules: {
        subject_id: { required: true },
        name: { required: true, minlength: 3 },
        user_id: { required: true },
        upload_type: { required: true },
        duration: {
            required: function() {
                return $("#upload_type").val() === "drive_link";
            },
            hhmmss: true
        }
    },
    messages: {
        subject_id: { required: "Please select a subject" },
        name: { required: "Enter a video name", minlength: "At least 3 characters" },
        user_id: { required: "Please select an uploader" },
        upload_type: { required: "Please select an upload type" },
        duration: {
            required: "Please enter video duration for Google Drive videos"
        }
    },
    submitHandler: function (form) {
        const formData = new FormData(form);
        formData.append('_token', "{{ csrf_token() }}");

        $("#uploadProgressBar").val(0).show();

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
                let xhr = new XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (e) {
                    if (e.lengthComputable) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        $("#uploadProgressBar").val(percent);
                    }
                }, false);
                return xhr;
            },
            beforeSend: () => {
                $(':input[type="submit"]').prop('disabled', true);
            },
            success: function (response) {
                toastr.success(response.message);
                $(".modal").modal('hide');
                $('#subjects-video-table').DataTable().ajax.reload();
            },
            error: function (response) {
                toastr.error(response.responseJSON?.message || 'Something went wrong.');
            },
            complete: () => {
                $(':input[type="submit"]').prop('disabled', false);
                $("#uploadProgressBar").hide();
            }
        });

        return false; // ❗ Prevent default form submission
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

    function onPlayerReady(event) {
        setTimeout(() => {
            const durationInSeconds = player.getDuration();
            const minutes = Math.floor(durationInSeconds / 60).toString().padStart(2, '0');
            const seconds = Math.floor(durationInSeconds % 60).toString().padStart(2, '0');
            const formatted = `00:${minutes}:${seconds}`;
            $('#duration-display').text(`Video Duration: ${minutes} min ${seconds} sec`);
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
</script>


<script>
    $('#drive_link').on('input', function() {
        const url = $(this).val();
        const match = url.match(/\/d\/([a-zA-Z0-9_-]{10,})/);

        if (match && match[1]) {
            const fileId = match[1];
            const previewUrl = `https://drive.google.com/file/d/${fileId}/preview`;
            $('#drive-preview-iframe').attr('src', previewUrl);
            $('#drive-preview').show();
        } else {
            $('#drive-preview').hide();
            $('#drive-preview-iframe').attr('src', '');
        }
    });
</script>

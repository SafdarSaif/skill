<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Add Subject Video</h3>
        <p class="text-muted">Fill in the subject video details below</p>
    </div>

    <form id="subject-video-form" action="{{ route('subjectvideo.store') }}" method="POST" enctype="multipart/form-data"
        class="row g-3">
        @csrf

        <!-- Subject Selection -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

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
                title="Enter duration in HH:MM:SS format">
        </div>


        <!-- Uploader -->
        <div class="col-md-6">
            <label for="user_id" class="form-label">Uploader <span class="text-danger">*</span></label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Select Uploader</option>
                @foreach ($users as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        {{-- <!-- Position -->
        <div class="col-md-6">
            <label for="position" class="form-label">Position</label>
            <input type="number" name="position" id="position" class="form-control" value="0">
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
        {{-- <div class="col-md-12" id="youtube_field">
            <label for="video_url" class="form-label">YouTube Video URL <span class="text-danger">*</span></label>
            <input type="url" name="video_url" id="video_url" class="form-control">
        </div> --}}

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

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Save Video</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<!-- jQuery Script for Dynamic Field Display -->
{{-- <script>
    $(document).ready(function() {
        $('#upload_type').change(function() {
            if ($(this).val() === 'youtube') {
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
        });

        // Form Validation
        $("#subject-video-form").validate({
            rules: {
                subject_id: {
                    required: true
                },
                name: {
                    required: true,
                    minlength: 3
                },
                user_id: {
                    required: true
                },
                upload_type: {
                    required: true
                },
                video_url: {
                    required: function() {
                        return $("#upload_type").val() === "youtube";
                    },
                    url: true,
                    pattern: /^https?:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+$/ 
                video_file: {
                    required: function() {
                        return $("#upload_type").val() === "local";
                    },
                    extension: "mp4|avi|mkv|mov"
                }
            },
            messages: {
                subject_id: "Please select a subject",
                name: {
                    required: "Please enter a video name",
                    minlength: "Video name must be at least 3 characters long"
                },
                user_id: "Please select an uploader",
                upload_type: "Please select an upload type",
                video_url: {
                    required: "Please enter a YouTube embedded URL.",
                    url: "Enter a valid URL.",
                    pattern: "Only YouTube embedded URLs are allowed (e.g., https://www.youtube.com/embed/VIDEO_ID)."
                },
                video_file: "Please upload a valid video file (mp4, avi, mkv, mov)"
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
</script> --}}


{{-- <script>
    $(document).ready(function() {
        $('#upload_type').change(function() {
            if ($(this).val() === 'youtube') {
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
        });

        // Form Validation
        $("#subject-video-form").validate({
            rules: {
                subject_id: {
                    required: true
                },
                name: {
                    required: true,
                    minlength: 3
                },
                user_id: {
                    required: true
                },
                upload_type: {
                    required: true
                },
                video_url: {
                    required: function() {
                        return $("#upload_type").val() === "youtube";
                    },
                    url: true,
                    pattern: /^https?:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+$/
                }, // **Fixed missing comma**
                video_file: {
                    required: function() {
                        return $("#upload_type").val() === "local";
                    },
                    extension: "mp4|avi|mkv|mov"
                }
            },
            messages: {
                subject_id: "Please select a subject",
                name: {
                    required: "Please enter a video name",
                    minlength: "Video name must be at least 3 characters long"
                },
                user_id: "Please select an uploader",
                upload_type: "Please select an upload type",
                video_url: {
                    required: "Please enter a YouTube embedded URL.",
                    url: "Enter a valid URL.",
                    pattern: "Only YouTube embedded URLs are allowed (e.g., https://www.youtube.com/embed/VIDEO_ID)."
                },
                video_file: "Please upload a valid video file (mp4, avi, mkv, mov)"
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
</script> --}}

<script>
    $(document).ready(function() {
        $('#upload_type').change(function() {
            if ($(this).val() === 'youtube') {
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
        });

        // Form Validation
        $("#subject-video-form").validate({
            rules: {
                subject_id: {
                    required: true
                },
                name: {
                    required: true,
                    minlength: 3
                },
                user_id: {
                    required: true
                },
                upload_type: {
                    required: true
                },
                video_url: {
                    required: function() {
                        return $("#upload_type").val() === "youtube";
                    },
                    url: true,
                    pattern: /^https?:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+$/
                },
                video_file: {
                    required: function() {
                        return $("#upload_type").val() === "local";
                    },
                    extension: "mp4|avi|mkv|mov"
                }
            },
            messages: {
                subject_id: "Please select a subject",
                name: {
                    required: "Please enter a video name",
                    minlength: "Video name must be at least 3 characters long"
                },
                user_id: "Please select an uploader",
                upload_type: "Please select an upload type",
                video_url: {
                    required: "Please enter a YouTube embedded URL.",
                    url: "Enter a valid URL.",
                    pattern: "Only YouTube embedded URLs are allowed (e.g., https://www.youtube.com/embed/VIDEO_ID)."
                },
                video_file: "Please upload a valid video file (mp4, avi, mkv, mov)"
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                formData.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $(':input[type="submit"]').prop('disabled', true);
                    },
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
                    error: function(xhr) {
                        $(':input[type="submit"]').prop('disabled', false);
                        var response = xhr.responseJSON;
                        if (response && response.message) {
                            toastr.error(response.message);
                        } else {
                            toastr.error(
                                "An unexpected error occurred. Please try again.");
                        }
                    }
                });
            }
        });
    });
</script>

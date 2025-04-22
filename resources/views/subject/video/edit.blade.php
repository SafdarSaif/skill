<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Subject Video</h3>
        <p class="text-muted">Update the subject video details below</p>
    </div>

    <form id="subject-video-form" action="{{ route('subjectvideo.update', $video->id) }}" method="POST"
        enctype="multipart/form-data" class="row g-3">
        @csrf

        <!-- Subject Selection -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                    <option value="{{ $id }}"
                        {{ old('subject_id', $video->subject_id) == $id ? 'selected' : '' }}>{{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Video Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">Video Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $video->name) }}" required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $video->description) }}</textarea>
        </div>

        <!-- Duration -->
        {{-- <div class="col-md-6">
            <label for="duration" class="form-label">Duration (HH:MM:SS)</label>
            <input type="text" name="duration" id="duration" class="form-control"
                value="{{ old('duration', $video->duration) }}" pattern="^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$"
                placeholder="HH:MM:SS" title="Enter duration in HH:MM:SS format">
        </div> --}}

        <!-- Duration -->
        {{-- <div class="col-md-6">
            <label for="duration" class="form-label">Duration (HH:MM:SS)</label>
            <input type="text" name="duration" id="duration" class="form-control"
                value="{{ old('duration', gmdate('H:i:s', $video->duration)) }}"
                pattern="^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$" placeholder="HH:MM:SS"
                title="Enter duration in HH:MM:SS format">
        </div> --}}

        <!-- Duration -->
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
                <option value="0" {{ old('position', $video->position) == 0 ? 'selected' : '' }}>General Videos
                    (0)</option>
                <option value="1" {{ old('position', $video->position) == 1 ? 'selected' : '' }}>Last Videos (1)
                </option>
            </select>
        </div>

        <!-- Upload Type -->
        <div class="col-md-6">
            <label for="upload_type" class="form-label">Upload Type <span class="text-danger">*</span></label>
            <select name="upload_type" id="upload_type" class="form-select" required>
                <option value="youtube" {{ old('upload_type', $video->upload_type) == 'youtube' ? 'selected' : '' }}>
                    YouTube</option>
                <option value="local" {{ old('upload_type', $video->upload_type) == 'local' ? 'selected' : '' }}>Local
                </option>
            </select>
        </div>

        <!-- YouTube Video URL -->
        <div class="col-md-12" id="youtube_field"
            style="{{ old('upload_type', $video->upload_type) == 'youtube' ? '' : 'display: none;' }}">
            <label for="video_url" class="form-label">YouTube Video URL <span class="text-danger">*</span></label>
            <input type="url" name="video_url" id="video_url" class="form-control"
                placeholder="Enter YouTube embedded URL" value="">
            <small class="text-muted">⚠ Only YouTube embedded URLs are allowed (e.g.,
                <code>https://www.youtube.com/embed/VIDEO_ID</code>).</small>
        </div>

        <!-- Local File Upload -->
        <div class="col-md-12" id="local_field"
            style="{{ old('upload_type', $video->upload_type) == 'local' ? '' : 'display: none;' }}">
            <label for="video_file" class="form-label">Upload Video File <span class="text-danger">*</span></label>
            <input type="file" name="video_file" id="video_file" class="form-control" accept="video/*">


            @if ($video->upload_type == 'local' && $video->video_url)
                <!-- Display existing video file name -->
                <p class="mt-2"><strong>Current File:</strong> {{ basename($video->video_url) }}</p>

                <!-- Video Player Preview -->
                <video width="100%" height="auto" controls class="mt-2">
                    <source src="{{ asset('storage/' . $video->video_url) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @endif
        </div>



        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update Video</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<!-- jQuery Script for Dynamic Field Display -->
<script>
    $(document).ready(function() {

        function toggleFields() {
            let uploadType = $('#upload_type').val();
            if (uploadType === 'youtube') {
                $('#youtube_field').show();
                $('#video_url').prop('required', true);
                $('#local_field').hide();
                $('#video_file').prop('required', false); // Ensure it's not required
            } else {
                $('#local_field').show();
                $('#video_file').prop('required', false); // Remove required here
                $('#youtube_field').hide();
                $('#video_url').prop('required', false);
            }
        }


        // Initialize on page load
        toggleFields();

        $('#upload_type').change(function() {
            toggleFields();
        });

        // Add it right HERE ↓
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

        // Form Submission via AJAX
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
                user_id: {
                    required: "Please select an uploader"
                },
                upload_type: {
                    required: "Please select an upload type"
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
                    success: function(response) {
                        toastr.success(response.message);
                        $(".modal").modal('hide');
                        $('#subjects-video-table').DataTable().ajax.reload();
                    },
                    error: function(response) {
                        toastr.error(response.responseJSON.message);
                    }
                });
            }
        });

    });
</script>

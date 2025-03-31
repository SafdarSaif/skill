<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('course_id');
            $table->string('subject_name');
            $table->float('total_duration');
            $table->float('watch_time');
            $table->float('progress');
            $table->timestamps();

            // Foreign Key Constraints (Assuming you have these tables)
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('subject_videos')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};

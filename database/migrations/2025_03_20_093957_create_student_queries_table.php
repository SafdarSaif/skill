<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_queries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('student_id');
            $table->text('doubt_question');
            $table->text('doubt_answer')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'answered', 'closed'])->default('pending');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('video_id')->references('id')->on('subject_videos')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_queries');
    }
};

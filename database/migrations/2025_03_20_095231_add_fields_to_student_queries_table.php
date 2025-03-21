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
        Schema::table('student_queries', function (Blueprint $table) {
            // Add columns with nullable() to prevent integrity constraint violations
            $table->unsignedBigInteger('video_id')->nullable()->after('id');
            $table->unsignedBigInteger('student_id')->nullable()->after('video_id');
            $table->text('answer')->nullable()->after('query');
            $table->string('attachment')->nullable()->after('answer');

            // Add foreign key constraints
            $table->foreign('video_id')->references('id')->on('subject_videos')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_queries', function (Blueprint $table) {
            // Drop foreign keys before dropping columns
            $table->dropForeign(['video_id']);
            $table->dropForeign(['student_id']);

            // Drop columns
            $table->dropColumn(['video_id', 'student_id', 'answer', 'attachment']);
        });
    }
};

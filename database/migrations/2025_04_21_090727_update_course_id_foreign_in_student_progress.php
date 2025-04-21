<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            $table->dropForeign(['course_id']);

            $table->dropIndex(['course_id']); 

            $table->index('course_id');

            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            $table->dropForeign(['course_id']);

            $table->dropIndex(['course_id']);

            $table->index('course_id');

            $table->foreign('course_id')
                ->references('id')->on('student_courses')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }
};

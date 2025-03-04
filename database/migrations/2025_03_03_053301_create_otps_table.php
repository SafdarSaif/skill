<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyInteger('otp');
            $table->dateTime('expire_at');
            $table->bigInteger('mobile_number');
            $table->boolean('is_used')->default(false);
            $table->unsignedBigInteger('students_id');
            $table->foreign('students_id')->references('id')->on('students')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};

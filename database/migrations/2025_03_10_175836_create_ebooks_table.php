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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade'); 
            $table->string('name'); 
            $table->text('description')->nullable(); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('upload_type', ['pdf', 'url']); 
            $table->string('file_location')->nullable();  
            $table->text('external_link')->nullable(); 
            $table->tinyInteger('status')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};

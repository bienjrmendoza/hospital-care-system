<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_invites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('specialization')->nullable();
            $table->string('token')->unique();
            $table->timestamp('expires_at');
            $table->foreignId('created_by_admin_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['email', 'used_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_invites');
    }
};

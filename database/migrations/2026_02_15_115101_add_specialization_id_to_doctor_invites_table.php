<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor_invites', function (Blueprint $table) {
            $table->foreignId('specialization_id')
                ->nullable()
                ->after('specialization')
                ->constrained('specializations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('doctor_invites', function (Blueprint $table) {
            $table->dropConstrainedForeignId('specialization_id');
        });
    }
};

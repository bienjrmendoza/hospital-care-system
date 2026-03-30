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
        Schema::table('vitals', function (Blueprint $table) {
            $table->text('diagnostic')->nullable()->after('initial_assessment');
            $table->text('medication')->nullable()->after('diagnostic');
            $table->text('treatment')->nullable()->after('medication');
            $table->text('diet')->nullable()->after('treatment');
            $table->text('remarks')->nullable()->after('diet');
        });
    }

    public function down(): void
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropColumn([
                'diagnostic',
                'medication',
                'treatment',
                'diet',
                'remarks'
            ]);
        });
    }
};

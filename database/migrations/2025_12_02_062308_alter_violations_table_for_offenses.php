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
        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn('fee');
            $table->string('first_offense')->nullable();
            $table->string('second_offense')->nullable();
            $table->string('third_offense')->nullable();
            $table->string('fourth_offense')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->decimal('fee', 8, 2);
            $table->dropColumn(['first_offense', 'second_offense', 'third_offense', 'fourth_offense']);
        });
    }
};

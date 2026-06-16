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
        Schema::table('administrative_requests', function (Blueprint $table) {
            $table->text('admin_feedback')
                ->nullable()
                ->after('ai_feedback');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrative_requests', function (Blueprint $table) {
            //
        });
    }
};

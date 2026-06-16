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
        Schema::table('administrative_requests', function (Blueprint $table) {
            $table->string('student_attachment')->nullable()->after('description');
            $table->string('issued_document')->nullable()->after('admin_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrative_requests', function (Blueprint $table) {
            $table->dropColumn(['student_attachment', 'issued_document']);
        });
    }
};

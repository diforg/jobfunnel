<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->foreignUuid('user_id')->nullable()->after('id')
                ->constrained('users')->nullOnDelete();
            $table->index('user_id');
            // Unique skill name per user (NULL user_id also gets unique, but NULLs are not
            // equal in PostgreSQL so multiple NULL-user skills with same name are allowed).
            $table->unique(['user_id', 'name'], 'skills_user_id_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

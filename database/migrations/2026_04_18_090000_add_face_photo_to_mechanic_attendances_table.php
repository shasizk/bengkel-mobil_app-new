<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('mechanic_attendances', 'face_photo')) {
            Schema::table('mechanic_attendances', function (Blueprint $table) {
                $table->longText('face_photo')->nullable()->after('notes');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('mechanic_attendances', 'face_photo')) {
            Schema::table('mechanic_attendances', function (Blueprint $table) {
                $table->dropColumn('face_photo');
            });
        }
    }
};

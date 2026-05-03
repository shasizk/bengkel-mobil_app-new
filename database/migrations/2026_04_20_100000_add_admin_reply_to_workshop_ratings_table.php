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
        if (! Schema::hasTable('workshop_ratings')) {
            return;
        }

        Schema::table('workshop_ratings', function (Blueprint $table) {
            if (! Schema::hasColumn('workshop_ratings', 'admin_reply')) {
                $table->text('admin_reply')->nullable()->after('comment');
            }

            if (! Schema::hasColumn('workshop_ratings', 'responded_by')) {
                $table->foreignId('responded_by')->nullable()->after('admin_reply')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('workshop_ratings', 'responded_at')) {
                $table->timestamp('responded_at')->nullable()->after('responded_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('workshop_ratings')) {
            return;
        }

        Schema::table('workshop_ratings', function (Blueprint $table) {
            if (Schema::hasColumn('workshop_ratings', 'responded_by')) {
                $table->dropConstrainedForeignId('responded_by');
            }

            if (Schema::hasColumn('workshop_ratings', 'responded_at')) {
                $table->dropColumn('responded_at');
            }

            if (Schema::hasColumn('workshop_ratings', 'admin_reply')) {
                $table->dropColumn('admin_reply');
            }
        });
    }
};

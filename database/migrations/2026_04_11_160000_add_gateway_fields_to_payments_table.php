<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        $hasSnapToken = Schema::hasColumn('payments', 'snap_token');
        $hasPaymentUrl = Schema::hasColumn('payments', 'payment_url');
        $hasQrString = Schema::hasColumn('payments', 'qr_string');
        $hasGatewayStatus = Schema::hasColumn('payments', 'gateway_status');
        $hasGatewayResponse = Schema::hasColumn('payments', 'gateway_response');
        $hasPaidAt = Schema::hasColumn('payments', 'paid_at');

        Schema::table('payments', function (Blueprint $table) use ($hasSnapToken, $hasPaymentUrl, $hasQrString, $hasGatewayStatus, $hasGatewayResponse, $hasPaidAt) {
            if (! $hasSnapToken) {
                $table->string('snap_token')->nullable()->after('payment_status');
            }

            if (! $hasPaymentUrl) {
                $table->text('payment_url')->nullable()->after('snap_token');
            }

            if (! $hasQrString) {
                $table->longText('qr_string')->nullable()->after('payment_url');
            }

            if (! $hasGatewayStatus) {
                $table->string('gateway_status')->nullable()->after('qr_string');
            }

            if (! $hasGatewayResponse) {
                $table->longText('gateway_response')->nullable()->after('gateway_status');
            }

            if (! $hasPaidAt) {
                $table->timestamp('paid_at')->nullable()->after('gateway_response');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        $columnsToDrop = [];

        foreach (['snap_token', 'payment_url', 'qr_string', 'gateway_status', 'gateway_response', 'paid_at'] as $column) {
            if (Schema::hasColumn('payments', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if ($columnsToDrop === []) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) use ($columnsToDrop) {
            $table->dropColumn($columnsToDrop);
        });
    }
};

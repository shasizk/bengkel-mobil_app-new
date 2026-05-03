<?php

namespace App\Console\Commands;

use App\Mail\ServiceReminderMail;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendServiceReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bengkel:send-service-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send email reminders for bookings scheduled today.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $bookings = Booking::with(['user', 'vehicle', 'services.service', 'transaction.payment'])
            ->whereDate('booking_date', Carbon::today()->toDateString())
            ->whereNull('service_reminder_sent_at')
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No service reminders to send today.');

            return self::SUCCESS;
        }

        foreach ($bookings as $booking) {
            if (! $booking->user?->email) {
                $this->warn("Booking #{$booking->id} skipped: customer email not found.");
                continue;
            }

            Mail::to($booking->user->email)->send(new ServiceReminderMail($booking));

            $booking->forceFill([
                'service_reminder_sent_at' => now(),
            ])->save();

            $this->info("Reminder sent for booking #{$booking->id} to {$booking->user->email}.");
        }

        return self::SUCCESS;
    }
}

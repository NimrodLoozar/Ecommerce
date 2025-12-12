<?php

namespace App\Console\Commands;

use App\Jobs\SendTestDriveReminderEmail;
use App\Models\TestDrive;
use Illuminate\Console\Command;

class SendTestDriveReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-drives:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for test drives scheduled for tomorrow';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $testDrives = TestDrive::where('status', 'confirmed')
            ->whereDate('preferred_date', $tomorrow)
            ->with(['user', 'car'])
            ->get();

        $count = 0;

        foreach ($testDrives as $testDrive) {
            SendTestDriveReminderEmail::dispatch($testDrive);
            $count++;
        }

        $this->info("Sent {$count} test drive reminder(s).");

        return Command::SUCCESS;
    }
}

<?php

namespace App\Jobs;

use App\Mail\TestDriveReminderMail;
use App\Models\TestDrive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTestDriveReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public TestDrive $testDrive
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Only send reminder if test drive is still confirmed
        if ($this->testDrive->status === 'confirmed') {
            Mail::to($this->testDrive->user->email)
                ->send(new TestDriveReminderMail($this->testDrive));
        }
    }
}

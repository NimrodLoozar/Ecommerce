<?php

namespace App\Jobs;

use App\Mail\InquiryReceivedMail;
use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInquiryReceivedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Inquiry $inquiry
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send email to dealer if car has a dealer
        if ($this->inquiry->car && $this->inquiry->car->dealer) {
            Mail::to($this->inquiry->car->dealer->user->email)
                ->send(new InquiryReceivedMail($this->inquiry));
        } else {
            // Send to admin email if no dealer (platform-owned cars)
            $adminEmail = config('mail.from.address');
            if ($adminEmail) {
                Mail::to($adminEmail)
                    ->send(new InquiryReceivedMail($this->inquiry));
            }
        }
    }
}

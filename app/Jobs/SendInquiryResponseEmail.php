<?php

namespace App\Jobs;

use App\Mail\InquiryResponseMail;
use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInquiryResponseEmail implements ShouldQueue
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
        // Send email to customer who submitted the inquiry
        Mail::to($this->inquiry->user->email)
            ->send(new InquiryResponseMail($this->inquiry));
    }
}

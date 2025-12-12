<?php

namespace App\Jobs;

use App\Mail\AbandonedCartMail;
use App\Models\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAbandonedCartEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Cart $cart
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send email to the cart owner
        Mail::to($this->cart->user->email)
            ->send(new AbandonedCartMail($this->cart));
    }
}

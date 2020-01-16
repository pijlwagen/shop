<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     * @param order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = Order::with(['payment', 'items', 'address'])->find($order->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('app.mail'))
            ->subject(config('app.name') . ' Order')
            ->view('mail.order.confirmation', [
                'order' => $this->order
            ]);
    }
}

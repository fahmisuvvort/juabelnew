<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminNotificationOrder extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $invoiceId = $this->order->order_ref;
        $paymentMethod = $this->order->transaction->payment_name;
        $name = $this->order->customer_name;
        $email = $this->order->customer_email;
        $phone = $this->order->customer_whatsapp;
        $courier = $this->order->shipping_courier_name;
        $total = 'Rp. ' . number_format($this->order->order_total,0,",",".");
        $tgl = $this->order->created_at;

        return (new MailMessage)
                    ->greeting('Helo, Admin ' . config('app.name'))
                    ->line('New Order has been created!')
                    ->line('Order Details:')
                    ->line('Invoice ID : ' . $invoiceId)
                    ->line('Customer Name : ' . $name)
                    ->line('Customer Email : ' . $email)
                    ->line('Customer Phone: ' . $phone)
                    ->line('Created : ' . $tgl)
                    ->line('Order Total : ' . $total)
                    ->line('Courier : ' . $courier)
                    ->line('Payment Method : ' . $paymentMethod);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

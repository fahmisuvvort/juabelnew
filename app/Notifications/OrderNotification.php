<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderNotification extends Notification
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
        $courier = $this->order->shipping_courier_name;
        $total = 'Rp. ' . number_format($this->order->order_total,0,",",".");
        $url = $this->order->url;
        $tgl = $this->order->created_at;
        $expired = Carbon::createFromTimestamp($this->order->transaction->expired_time)->toDateTimeString();

        return (new MailMessage)
                    ->greeting('Halo, Yth '.$name)
                    ->line('Terima kasih sudah berbelanja di ' . config('app.name'). '. Berikut detil pesanan anda :')
                    ->line('Nama : ' . $name)
                    ->line('Invoice ID : ' . $invoiceId)
                    ->line('Dibuat : ' . $tgl)
                    ->line('Total : ' . $total)
                    ->line('Kurir : ' . $courier)
                    ->line('Metode Pembayaran : ' . $paymentMethod)
                    ->action('Lihat detil Order', $url)
                    ->line('Segera lakukan pembayaran sebelum ' . $expired . ' agar pesananmu dapat diproses, Terimakasih.');
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

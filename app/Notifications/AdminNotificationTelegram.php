<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class AdminNotificationTelegram extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $order;
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
      return [TelegramChannel::class];
  }

  /**
    * Get the mail representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\MailMessage
    */
  public function toTelegram($notifiable)
  {
      $invoiceId = $this->order->order_ref;
      $paymentMethod = $this->order->transaction->payment_name;
      $name = $this->order->customer_name;
      $phone = $this->order->customer_whatsapp;
      $courier = $this->order->shipping_courier_name;
      $total = 'Rp. ' . number_format($this->order->order_total,0,",",".");
      $url = $this->order->url;
      $tgl = $this->order->created_at;

      return TelegramMessage::create()
          // Markdown supported.
          ->content("Halo admin!\nAda pesanan baru \nTgl Pesan: $tgl \nInvoice: $invoiceId \nPembayaran: $paymentMethod \nNama Customer: $name \nNo Whatsapp: $phone \nKurir: $courier \nTotal Order: $total\n\nRef Order: $url");
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

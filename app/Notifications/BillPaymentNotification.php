<?php

namespace App\Notifications;

use App\Models\NewBill;
use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BillPaymentNotification extends Notification
{
    use Queueable;

    protected $bill;
    protected $provider;

    /**
     * Create a new notification instance.
     *
     * @param NewBill $bill
     * @param Provider $provider
     */
    public function __construct(NewBill $bill, Provider $provider)
    {
        $this->bill = $bill;
        $this->provider = $provider;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Befizetetlen számla')
                    ->markdown('mail.bill_payment', ['provider' => $this->provider, 'bill' => $this->bill]);
    }

    public function toDatabase()
    {
        return [
            'name' => $this->provider->label,
            'day' => $this->bill->remaining_day,
        ];
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

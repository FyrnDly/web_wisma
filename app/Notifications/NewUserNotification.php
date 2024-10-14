<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    public $username;
    public $full_name;
    public $email;
    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($username, $full_name, $email, $password)
    {
        $this->username = $username;
        $this->full_name = $full_name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembuatan Akun '.$this->username)
            ->greeting('Hallo! '.$this->full_name)
            ->line('Akun anda berhasil dibuat dengan username dan password sebagai berikut.')
            ->line('Username :'.$this->username)
            ->line('Email :'.$this->email)
            ->line('Password :'.$this->password)
            ->action('Masuk Sekarang', route('filament.app.auth.login'))
            ->line('Jangan lupa lakukan perubahan password!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

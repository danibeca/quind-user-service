<?php

namespace App\Utils\Notifications;

use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification {

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new CustomMailMessage)
            ->greeting('Hola')
            ->subject('Cambio de password')
            ->line('Estas recibiendo este email, porque hemos recibido una solicitud de cambio de password para tu cuenta.')
            ->action('Reinicia tu password', env('APP_FRONT') . '/password/reset/' . $this->token)
            ->line('Si no has realizado esta solicitud, por favor haga caso omiso de este mensaje.');
    }
}

<?php


namespace App\Utils\Notifications;

use Illuminate\Notifications\Messages\SimpleMessage;

class CustomMailMessage extends SimpleMessage {

    public $view = [
        'notifications.email',
        'notifications.email-plain',
    ];

    public $viewData = [];
    public $from = [];
    public $to = [];
    public $cc = [];
    public $bcc = [];
    public $replyTo = [];
    public $attachments = [];
    public $rawAttachments = [];
    public $priority;

    public function view($view, array $data = [])
    {
        $this->view = $view;
        $this->viewData = $data;

        return $this;
    }

    public function from($address, $name = null)
    {
        $this->from = [$address, $name];

        return $this;
    }

    public function to($address)
    {
        $this->to = $address;

        return $this;
    }

    public function cc($address)
    {
        $this->cc = $address;

        return $this;
    }

    public function replyTo($address, $name = null)
    {
        $this->replyTo = [$address, $name];

        return $this;
    }

    public function attach($file, array $options = [])
    {
        $this->attachments[] = compact('file', 'options');

        return $this;
    }

    public function attachData($data, $name, array $options = [])
    {
        $this->rawAttachments[] = compact('data', 'name', 'options');

        return $this;
    }

    public function priority($level)
    {
        $this->priority = $level;

        return $this;
    }

    public function data()
    {
        return array_merge($this->toArray(), $this->viewData);
    }
}

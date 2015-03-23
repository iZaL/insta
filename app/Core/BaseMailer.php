<?php
namespace App\Core;

use App\Core\Contracts\MailerContract;
use Config;
use Illuminate\Mail\Mailer;

abstract class BaseMailer implements MailerContract
{

    protected $mailer;
    protected $senderEmail;
    protected $sender;
    protected $recepient;
    protected $recepientName;
    protected $subject;
    protected $view;

    public function __construct(Mailer $mailer)
    {
        $this->mailer      = $mailer;
        $this->senderEmail = Config::get('mail.from.address');
        $this->sender      = Config::get('mail.from.name');
        $this->view        = 'emails.default';
    }

    public function fire(array $data)
    {
        try {
            $this->mailer->send($this->view, $data, function ($message) {
                $message
                    ->from($this->senderEmail, $this->sender)
                    ->sender($this->senderEmail, $this->sender)
                    ->to($this->recepient, $this->recepientName)
                    ->subject($this->subject);
            });
        } catch ( \Exception $e ) {
        }
    }
}
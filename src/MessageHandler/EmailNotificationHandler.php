<?php

namespace App\MessageHandler;

use App\Message\EmailNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class EmailNotificationHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(EmailNotification $message): void
    {
        $email = (new Email())
            ->from('test@gmail.com')
            ->subject($message->getSubject())
            ->to($message->getRecipient())
            ->text($message->getContent());
        $this->mailer->send($email);
    }
}
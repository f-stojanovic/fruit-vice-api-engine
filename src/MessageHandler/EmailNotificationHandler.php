<?php

namespace App\MessageHandler;

use App\Message\EmailNotification;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EmailNotificationHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly MailerInterface $mailer
    ) { }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(EmailNotification $message): void
    {
        $defaultEmail = $this->parameterBag->get('default_email');

        $email = (new Email())
            ->from($defaultEmail)
            ->subject($message->getSubject())
            ->to($message->getRecipient())
            ->text($message->getContent());
        $this->mailer->send($email);
    }
}
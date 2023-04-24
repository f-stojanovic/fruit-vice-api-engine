<?php

namespace App\Message;

class EmailNotification
{

    /**
     * EmailNotification constructor.
     *
     * @param string $recipient
     * @param string $subject
     * @param string $content
     */
    public function __construct(
        private readonly string $recipient,
        private readonly string $subject,
        private readonly string $content
    ) { }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
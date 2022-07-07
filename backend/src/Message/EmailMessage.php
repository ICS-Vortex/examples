<?php


namespace App\Message;


class EmailMessage
{
    private ?string $subject = null;
    private ?string $body = null;
    private ?array $recipients = [];
    private ?array $attachments = [];
    private ?bool $isHtml = false;

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return array|null
     */
    public function getRecipients(): ?array
    {
        return $this->recipients;
    }

    /**
     * @param array $recipients
     */
    public function setRecipients(array $recipients): void
    {
        $this->recipients = $recipients;
    }

    /**
     * @return array|null
     */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }

    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
     * @return bool
     */
    public function getIsHtml(): bool
    {
        return $this->isHtml;
    }

    /**
     * @param bool|null $isHtml
     */
    public function setIsHtml(?bool $isHtml): void
    {
        $this->isHtml = $isHtml;
    }
}
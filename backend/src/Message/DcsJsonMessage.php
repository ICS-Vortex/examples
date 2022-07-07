<?php

namespace App\Message;

use App\Entity\JsonMessage;

class DcsJsonMessage
{
    private string $json;

    public function __construct(string $json)
    {
        $this->json = $json;
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return $this->json;
    }

    public function setJson(string $json)
    {
        $this->json = $json;
    }
}

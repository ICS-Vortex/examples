<?php

namespace App\Entity\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class Server
{
    /**
     * @Groups({"app_event"})
     */
    private $identifier;

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }
}

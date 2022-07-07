<?php

namespace App\Entity\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class Airfield
{
    /**
     * @var string $name
     * @Groups("app_event")
     */
    private ?string $name;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }


}

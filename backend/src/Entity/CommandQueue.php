<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="commands_queue")
 * @ORM\Entity(repositoryClass="App\Repository\CommandQueueRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CommandQueue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commandName;
    /**
     * @ORM\Column(type="bigint")
     */
    private $identifierValue;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $executed = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $failed = false;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $executeStartTime;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $executeEndTime;

    public function getId()
    {
        return $this->id;
    }

    public function getCommandName()
    {
        return $this->commandName;
    }

    public function setCommandName($commandName)
    {
        $this->commandName = $commandName;

        return $this;
    }

    public function getIdentifierValue()
    {
        return $this->identifierValue;
    }

    public function setIdentifierValue($identifierValue)
    {
        $this->identifierValue = $identifierValue;

        return $this;
    }

    public function getExecuted()
    {
        return $this->executed;
    }

    public function setExecuted($executed)
    {
        $this->executed = $executed;

        return $this;
    }

    public function getFailed()
    {
        return $this->failed;
    }

    public function setFailed($failed)
    {
        $this->failed = $failed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExecuteStartTime()
    {
        return $this->executeStartTime;
    }

    /**
     * @param mixed $executeStartTime
     */
    public function setExecuteStartTime($executeStartTime)
    {
        $this->executeStartTime = $executeStartTime;
    }

    /**
     * @return mixed
     */
    public function getExecuteEndTime()
    {
        return $this->executeEndTime;
    }

    /**
     * @param mixed $executeEndTime
     */
    public function setExecuteEndTime($executeEndTime)
    {
        $this->executeEndTime = $executeEndTime;
    }

    /**
     * @ORM\PrePersist
     */
    public function setTimeValues()
    {
        $this->executeStartTime = new \DateTime();
        $this->executeEndTime = new \DateTime();
    }
}

<?php

namespace App\Entity\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class JsonMessage
{
    /**
     * @Groups({"app_event"})
     */
    private Initiator $init;

    /**
     * @Groups({"app_event"})
     */
    private Target $targ;

    /**
     * @Groups({"app_event"})
     */
    private string $time;

    /**
     * @Groups({"app_event"})
     */
    private Airfield $field;

    /**
     * @Groups({"app_event"})
     */
    private ?string $description;
    private ?string $email;

    /**
     * @Groups({"app_event"})
     */
    private ?string $mission;

    /**
     * @Groups({"app_event"})
     */
    private Server $server;

    /**
     * @Groups({"app_event"})
     */
    private string $event;

    /**
     * @Groups({"app_event"})
     */
    private Weather $weather;

    /**
     * @return Initiator
     */
    public function getInit(): ?Initiator
    {
        return $this->init;
    }

    /**
     * @param Initiator $init
     */
    public function setInit(Initiator $init): void
    {
        $this->init = $init;
    }

    /**
     * @return Target
     */
    public function getTarg(): ?Target
    {
        return $this->targ;
    }

    /**
     * @param Target $targ
     */
    public function setTarg(Target $targ): void
    {
        $this->targ = $targ;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime(string $time): void
    {
        $this->time = $time;
    }

    /**
     * @return Airfield
     */
    public function getField(): ?Airfield
    {
        return $this->field;
    }

    /**
     * @param Airfield $field
     */
    public function setField(Airfield $field): void
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getMission(): ?string
    {
        return $this->mission;
    }

    /**
     * @param string $mission
     */
    public function setMission(string $mission): void
    {
        $this->mission = $mission;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * @param Server $server
     */
    public function setServer(Server $server): void
    {
        $this->server = $server;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @param string $event
     */
    public function setEvent(string $event): void
    {
        $this->event = $event;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return Weather
     */
    public function getWeather(): ?Weather
    {
        return $this->weather;
    }

    /**
     * @param Weather $weather
     */
    public function setWeather(Weather $weather): void
    {
        $this->weather = $weather;
    }
}

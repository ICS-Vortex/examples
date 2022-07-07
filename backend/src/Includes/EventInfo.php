<?php

namespace App\Includes;

use Symfony\Component\Serializer\Annotation\Groups;

trait EventInfo
{
    /**
     * @Groups({"app_event"})
     */
    private $event;
    /**
     * @Groups({"app_event"})
     */
    private $time;

    /**
     * @Groups({"app_event"})
     */
    private $name;
    /**
     * @Groups({"app_event"})
     */
    private $theatre;
    /**
     * @Groups({"app_event"})
     */
    private $ip;
    /**
     * @Groups({"app_event"})
     */
    private $port;
    /**
     * @Groups({"app_event"})
     */
    private $ts;
    /**
     * @Groups({"app_event"})
     */
    private $srs;
    /**
     * @Groups({"app_event"})
     */
    private $id;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event): void
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port): void
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * @param mixed $ts
     */
    public function setTs($ts): void
    {
        $this->ts = $ts;
    }

    /**
     * @return mixed
     */
    public function getSrs()
    {
        return $this->srs;
    }

    /**
     * @param mixed $srs
     */
    public function setSrs($srs): void
    {
        $this->srs = $srs;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getTheatre()
    {
        return $this->theatre;
    }

    /**
     * @param mixed $theatre
     */
    public function setTheatre($theatre): void
    {
        $this->theatre = $theatre;
    }
}
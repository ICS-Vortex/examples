<?php

namespace App\Entity\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class Initiator
{
    /**
     * @Groups({"app_event"})
     */
    private $id;
    /**
     * @Groups({"app_event"})
     */
    private $ip;
    /**
     * @Groups({"app_event"})
     */
    private $email;
    /**
     * @Groups({"app_event"})
     */
    private $nick;
    /**
     * @Groups({"app_event"})
     */
    private $side;
    /**
     * @Groups({"app_event"})
     */
    private $gr;
    /**
     * @Groups({"app_event"})
     */
    private $cat;
    /**
     * @Groups({"app_event"})
     */
    private $hum;
    /**
     * @Groups({"app_event"})
     */
    private $type;
    /**
     * @var string $score
     * @Groups("app_event")
     */
    private $score;


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
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @param mixed $nick
     */
    public function setNick($nick): void
    {
        $this->nick = $nick;
    }

    /**
     * @return mixed
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * @param mixed $side
     */
    public function setSide($side): void
    {
        $this->side = $side;
    }

    /**
     * @return mixed
     */
    public function getGr()
    {
        return $this->gr;
    }

    /**
     * @param mixed $gr
     */
    public function setGr($gr): void
    {
        $this->gr = $gr;
    }

    /**
     * @return mixed
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * @param mixed $cat
     */
    public function setCat($cat): void
    {
        $this->cat = $cat;
    }

    /**
     * @return mixed
     */
    public function getHum()
    {
        return $this->hum;
    }

    /**
     * @param mixed $hum
     */
    public function setHum($hum): void
    {
        $this->hum = $hum;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore($score): void
    {
        $this->score = $score;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
}
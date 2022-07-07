<?php

namespace App\Entity\Model;

use Symfony\Component\Validator\Constraints as Assert;

class FinishRegistration
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Password length must be at least {{ limit }} characters long",
     *      maxMessage = "Password length cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;
    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "RepeatPassword length must be at least {{ limit }} characters long",
     *      maxMessage = "RepeatPassword length cannot be longer than {{ limit }} characters"
     * )
     */
    private $repeatPassword;
    /**
     * @Assert\NotBlank
     */
    private $token;

    private $about;

    private $birthday;

    private $favouritePlane;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $surname;

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRepeatPassword()
    {
        return $this->repeatPassword;
    }

    /**
     * @param mixed $repeatPassword
     */
    public function setRepeatPassword($repeatPassword): void
    {
        $this->repeatPassword = $repeatPassword;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getFavouritePlane()
    {
        return $this->favouritePlane;
    }

    /**
     * @param mixed $favouritePlane
     */
    public function setFavouritePlane($favouritePlane): void
    {
        $this->favouritePlane = $favouritePlane;
    }

    /**
     * @return mixed
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param mixed $about
     */
    public function setAbout($about): void
    {
        $this->about = $about;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }
}

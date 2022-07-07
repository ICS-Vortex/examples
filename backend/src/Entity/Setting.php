<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Setting
 *
 * @ORM\Table(name="settings", indexes={@ORM\Index(name="keyword_idx", columns={"keyword"})})
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="keyword", type="string", length=255, unique=true)
     */
    private $keyword;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;
    /**
     * @var string
     *
     * @ORM\Column(name="default_value", type="string", length=255)
     */
    private $defaultValue;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     */
    public function setName($name) : void
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     *
     */
    public function setKeyword($keyword) : void
    {
        $this->keyword = $keyword;
    }

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword() : ?string
    {
        return $this->keyword;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     */
    public function setValue($value) : void
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue() : ?string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) : void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDefaultValue() : ?string
    {
        return $this->defaultValue;
    }

    public function canBeEnabledOrDisabled() : bool
    {
        return $this->value === '1' || $this->value === '0';
    }
    /**
     * @param string $defaultValue
     */
    public function setDefaultValue($defaultValue) : void
    {
        $this->defaultValue = $defaultValue;
    }

    public function isEnabled() :bool
    {
        switch ($this->value) {
            case '1':
                return true;

            case '0':
            default: return false;
        }
    }
}


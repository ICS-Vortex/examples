<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;

/**
 * JsonMessage
 *
 * @ORM\Table(name="json_messages", options={"collate"="utf8_general_ci", "charset"="utf8"})
 * @ORM\Entity(repositoryClass="App\Repository\JsonMessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class JsonMessage
{
    use Timestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var Server
     *
     * @ORM\ManyToOne(targetEntity="Server")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id", nullable=true)
     */
    private $server;
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="deprecated", type="boolean", options={"default": 0})
     */
    private $deprecated = false;
    /**
     * @var bool
     *
     * @ORM\Column(name="executed", type="boolean", options={"default": 0})
     */
    private $executed = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="success", type="boolean", options={"default": 0})
     */
    private $success = false;
    /**
     * @ORM\Column(name="execute_time",type="decimal", precision=10, scale=6, nullable=true)
     */
    private $executeTime;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set executed
     *
     * @param boolean $executed
     *
     * @return JsonMessage
     */
    public function setExecuted($executed)
    {
        $this->executed = $executed;

        return $this;
    }

    /**
     * Get executed
     *
     * @return bool
     */
    public function getExecuted()
    {
        return $this->executed;
    }

    /**
     * @return mixed
     */
    public function getExecuteTime()
    {
        return $this->executeTime;
    }

    /**
     * @param mixed $executeTime
     */
    public function setExecuteTime($executeTime)
    {
        $this->executeTime = $executeTime;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return bool
     */
    public function isDeprecated()
    {
        return $this->deprecated;
    }

    /**
     * @param bool $deprecated
     */
    public function setDeprecated($deprecated)
    {
        $this->deprecated = $deprecated;
    }

    /**
     * Get deprecated
     *
     * @return boolean
     */
    public function getDeprecated()
    {
        return $this->deprecated;
    }

    /**
     * Get success
     *
     * @return boolean
     */
    public function getSuccess()
    {
        return $this->success;
    }

    public function decode() {
        return json_decode($this->content);
    }

    /**
     * @return Server|null
     */
    public function getServer(): ?Server
    {
        return $this->server;
    }

    /**
     * @param Server|null $server
     */
    public function setServer(?Server $server): void
    {
        $this->server = $server;
    }

    public function __construct($json = null)
    {
        $this->content = $json;
    }
}

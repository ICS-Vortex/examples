<?php

namespace App\Entity;

use App\Includes\Timestamp;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="logs")
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Log
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
     * @var Tour
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id")
     */
    public $tour;
    /**
     * @var Server
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="dogfights")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @var string
     *
     * @ORM\Column(name="event", type="string")
     */
    private $event;
    /**
     * @var string
     *
     * @ORM\Column(name="theatre", type="string", nullable=true)
     */
    private $theatre;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="event_time", type="datetime")
     */
    private $eventTime;
    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="simulation_time", type="datetime", nullable=true)
     */
    private $simulationTime;
    /**
     * @var string|null
     *
     * @ORM\Column(name="chat_message", type="string", nullable=true)
     */
    private $chatMessage;
    /**
     * @var string|null
     *
     * @ORM\Column(name="initiator_nickname", type="string", length=255, nullable=true)
     */
    private $initiatorNickname;
    /**
     * @var string|null
     *
     * @ORM\Column(name="initiator_email", type="string", length=255, nullable=true)
     */
    private $initiatorEmail;
    /**
     * @var string|null
     *
     * @ORM\Column(name="initiator_side", type="string", length=255, nullable=true)
     */
    private $initiatorSide;
    /**
     * @var string|null
     *
     * @ORM\Column(name="initiator_ip_address", type="string", length=255, nullable=true)
     */
    private $initiatorIpAddress;
    /**
     * @var string|null
     *
     * @ORM\Column(name="initiator_ucid", type="string", length=255, nullable=true)
     */
    private $initiatorUcid;
    /**
     * @var string|null
     *
     * @ORM\Column(name="initiator_type", type="string", length=255, nullable=true)
     */
    private $initiatorType;
    /**
     * @var string|null
     *
     * @ORM\Column(name="initiator_category", type="string", length=255, nullable=true)
     */
    private $initiatorCategory;
    /**
     * @var integer|null
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var string|null
     *
     * @ORM\Column(name="target_nickname", type="string", length=255, nullable=true)
     */
    private $targetNickname;
    /**
     * @var string|null
     *
     * @ORM\Column(name="target_side", type="string", length=255, nullable=true)
     */
    private $targetSide;
    /**
     * @var string|null
     *
     * @ORM\Column(name="target_ip", type="string", length=255, nullable=true)
     */
    private $targetIpAddress;
    /**
     * @var string|null
     *
     * @ORM\Column(name="target_ucid", type="string", length=255, nullable=true)
     */
    private $targetUcid;
    /**
     * @var string|null
     *
     * @ORM\Column(name="target_type", type="string", length=255, nullable=true)
     */
    private $targetType;
    /**
     * @var string|null
     *
     * @ORM\Column(name="target_category", type="string", length=255, nullable=true)
     */
    private $targetCategory;
    /**
     * @var boolean|null
     *
     * @ORM\Column(name="target_is_ground", type="boolean", nullable=true)
     */
    private $targetIsGround;
    /**
     * @var boolean|null
     *
     * @ORM\Column(name="target_is_human", type="boolean", nullable=true)
     */
    private $targetIsHuman;
    /**
     * @var array|null
     *
     * @ORM\Column(name="messages_stack", type="json", nullable=true)
     */
    private $messagesStack;
    /**
     * @var array|null
     *
     * @ORM\Column(name="weather", type="json", nullable=true)
     */
    private $weather;
    /**
     * @var array|null
     *
     * @ORM\Column(name="banlist", type="json", nullable=true)
     */
    private $banlist;
    /**
     * @var string|null
     *
     * @ORM\Column(name="field", type="json", nullable=true)
     */
    private $field;
    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    /**
     * @var string|null
     *
     * @ORM\Column(name="won", type="string", length=5, nullable=true)
     */
    private $won;
    /**
     * @var boolean
     *
     * @ORM\Column(name="success", type="boolean", options={"default":false})
     */
    private $success = false;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return DateTime|null
     */
    public function getSimulationTime(): ?DateTime
    {
        return $this->simulationTime;
    }

    /**
     * @param DateTime|null $simulationTime
     */
    public function setSimulationTime(?DateTime $simulationTime): void
    {
        $this->simulationTime = $simulationTime;
    }

    /**
     * @return string|null
     */
    public function getInitiatorNickname(): ?string
    {
        return $this->initiatorNickname;
    }

    /**
     * @param string|null $initiatorNickname
     */
    public function setInitiatorNickname(?string $initiatorNickname): void
    {
        $this->initiatorNickname = $initiatorNickname;
    }

    /**
     * @return string|null
     */
    public function getInitiatorSide(): ?string
    {
        return $this->initiatorSide;
    }

    /**
     * @param string|null $initiatorSide
     */
    public function setInitiatorSide(?string $initiatorSide): void
    {
        $this->initiatorSide = $initiatorSide;
    }

    /**
     * @return string|null
     */
    public function getInitiatorIpAddress(): ?string
    {
        return $this->initiatorIpAddress;
    }

    /**
     * @param string|null $initiatorIpAddress
     */
    public function setInitiatorIpAddress(?string $initiatorIpAddress): void
    {
        $this->initiatorIpAddress = $initiatorIpAddress;
    }

    /**
     * @return string|null
     */
    public function getInitiatorUcid(): ?string
    {
        return $this->initiatorUcid;
    }

    /**
     * @param string|null $initiatorUcid
     */
    public function setInitiatorUcid(?string $initiatorUcid): void
    {
        $this->initiatorUcid = $initiatorUcid;
    }

    /**
     * @return string|null
     */
    public function getInitiatorType(): ?string
    {
        return $this->initiatorType;
    }

    /**
     * @param string|null $initiatorType
     */
    public function setInitiatorType(?string $initiatorType): void
    {
        $this->initiatorType = $initiatorType;
    }

    /**
     * @return string|null
     */
    public function getInitiatorCategory(): ?string
    {
        return $this->initiatorCategory;
    }

    /**
     * @param string|null $initiatorCategory
     */
    public function setInitiatorCategory(?string $initiatorCategory): void
    {
        $this->initiatorCategory = $initiatorCategory;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @param int|null $score
     */
    public function setScore(?int $score): void
    {
        $this->score = $score;
    }

    /**
     * @return string|null
     */
    public function getTargetNickname(): ?string
    {
        return $this->targetNickname;
    }

    /**
     * @param string|null $targetNickname
     */
    public function setTargetNickname(?string $targetNickname): void
    {
        $this->targetNickname = $targetNickname;
    }

    /**
     * @return string|null
     */
    public function getTargetSide(): ?string
    {
        return $this->targetSide;
    }

    /**
     * @param string|null $targetSide
     */
    public function setTargetSide(?string $targetSide): void
    {
        $this->targetSide = $targetSide;
    }

    /**
     * @return string|null
     */
    public function getTargetIpAddress(): ?string
    {
        return $this->targetIpAddress;
    }

    /**
     * @param string|null $targetIpAddress
     */
    public function setTargetIpAddress(?string $targetIpAddress): void
    {
        $this->targetIpAddress = $targetIpAddress;
    }

    /**
     * @return string|null
     */
    public function getTargetUcid(): ?string
    {
        return $this->targetUcid;
    }

    /**
     * @param string|null $targetUcid
     */
    public function setTargetUcid(?string $targetUcid): void
    {
        $this->targetUcid = $targetUcid;
    }

    /**
     * @return string|null
     */
    public function getTargetType(): ?string
    {
        return $this->targetType;
    }

    /**
     * @param string|null $targetType
     */
    public function setTargetType(?string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * @return string|null
     */
    public function getTargetCategory(): ?string
    {
        return $this->targetCategory;
    }

    /**
     * @param string|null $targetCategory
     */
    public function setTargetCategory(?string $targetCategory): void
    {
        $this->targetCategory = $targetCategory;
    }

    /**
     * @return bool|null
     */
    public function getTargetIsGround(): ?bool
    {
        return $this->targetIsGround;
    }

    /**
     * @param bool|null $targetIsGround
     */
    public function setTargetIsGround(?bool $targetIsGround): void
    {
        $this->targetIsGround = $targetIsGround;
    }

    /**
     * @return bool|null
     */
    public function getTargetIsHuman(): ?bool
    {
        return $this->targetIsHuman;
    }

    /**
     * @param bool|null $targetIsHuman
     */
    public function setTargetIsHuman(?bool $targetIsHuman): void
    {
        $this->targetIsHuman = $targetIsHuman;
    }

    /**
     * @return array|null
     */
    public function getMessagesStack(): ?array
    {
        return $this->messagesStack;
    }

    /**
     * @param array|null $messagesStack
     */
    public function setMessagesStack(?array $messagesStack): void
    {
        $this->messagesStack = $messagesStack;
    }

    /**
     * @return string|null
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * @param string|null $field
     */
    public function setField(?string $field): void
    {
        $this->field = $field;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return Tour
     */
    public function getTour(): Tour
    {
        return $this->tour;
    }

    /**
     * @param Tour $tour
     */
    public function setTour(Tour $tour): void
    {
        $this->tour = $tour;
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
     * @return string|null
     */
    public function getChatMessage(): ?string
    {
        return $this->chatMessage;
    }

    /**
     * @param string|null $chatMessage
     */
    public function setChatMessage(?string $chatMessage): void
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * @return string
     */
    public function getTheatre(): string
    {
        return $this->theatre;
    }

    /**
     * @param string $theatre
     */
    public function setTheatre(?string $theatre): void
    {
        $this->theatre = $theatre;
    }

    /**
     * @return array|null
     */
    public function getWeather(): ?array
    {
        return $this->weather;
    }

    /**
     * @param array|null $weather
     */
    public function setWeather(?array $weather): void
    {
        $this->weather = $weather;
    }

    /**
     * @return array|null
     */
    public function getBanlist(): ?array
    {
        return $this->banlist;
    }

    /**
     * @param array|null $banlist
     */
    public function setBanlist(?array $banlist): void
    {
        $this->banlist = $banlist;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getWon(): ?string
    {
        return $this->won;
    }

    /**
     * @param string|null $won
     */
    public function setWon(?string $won): void
    {
        $this->won = $won;
    }

    /**
     * @return string|null
     */
    public function getInitiatorEmail(): ?string
    {
        return $this->initiatorEmail;
    }

    /**
     * @param string|null $initiatorEmail
     */
    public function setInitiatorEmail(?string $initiatorEmail): void
    {
        $this->initiatorEmail = $initiatorEmail;
    }

    /**
     * @return DateTime
     */
    public function getEventTime(): DateTime
    {
        return $this->eventTime;
    }

    /**
     * @param DateTime $eventTime
     */
    public function setEventTime(DateTime $eventTime): void
    {
        $this->eventTime = $eventTime;
    }
}

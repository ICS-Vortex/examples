<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Faq
 *
 * @ORM\Table(name="faqs", options={"collate"="utf8_general_ci", "charset"="utf8"})
 * @ORM\Entity(repositoryClass="App\Repository\FaqRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Faq
{
    use Timestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_faq", "api_tournaments"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Server")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id", nullable=true)
     */
    public $server;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255)
     * @Groups({"api_faq", "api_tournaments"})
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="questionEn", type="string", length=255)
     * @Groups({"api_faq", "api_tournaments"})
     */
    private $questionEn;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text")
     * @Groups({"api_faq", "api_tournaments"})
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="answerEn", type="text")
     * @Groups({"api_faq", "api_tournaments"})
     */
    private $answerEn;

    /**
     * @ORM\Column(name="for_sender", type="boolean", options={"default":1})
     */
    private $forSender = false;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="faqs")
     */
    private $tournament;

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
     * Set question
     *
     * @param string $question
     *
     * @return Faq
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set questionEn
     *
     * @param string $questionEn
     *
     * @return Faq
     */
    public function setQuestionEn($questionEn)
    {
        $this->questionEn = $questionEn;

        return $this;
    }

    /**
     * Get questionEn
     *
     * @return string
     */
    public function getQuestionEn()
    {
        return $this->questionEn;
    }

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return Faq
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set answerEn
     *
     * @param string $answerEn
     *
     * @return Faq
     */
    public function setAnswerEn($answerEn)
    {
        $this->answerEn = $answerEn;

        return $this;
    }

    /**
     * Get answerEn
     *
     * @return string
     */
    public function getAnswerEn()
    {
        return $this->answerEn;
    }

    /**
     * @return bool
     */
    public function isForSender(): bool
    {
        return $this->forSender;
    }

    /**
     * @param bool $forSender
     */
    public function setForSender($forSender = true): void
    {
        $this->forSender = $forSender;
    }

    /**
     * Set server
     *
     * @param Server|null $server
     * @return Faq
     */
    public function setServer(Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return Article
     */
    public function getServer()
    {
        return $this->server;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }
}

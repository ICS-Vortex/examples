<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\TournamentCouponRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TournamentCouponRepository::class)
 * @ORM\Table(name="tournament_coupons")
 * @ORM\HasLifecycleCallbacks
 */
class TournamentCoupon
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tournament_coupons"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="tournamentCoupons")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"tournament_coupons"})
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity=Pilot::class, inversedBy="tournamentCoupons")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"tournament_coupons"})
     */
    private $pilot;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tournament_coupons"})
     */
    private $code;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"tournament_coupons"})
     */
    private $couponDeadline;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPilot(): ?Pilot
    {
        return $this->pilot;
    }

    public function setPilot(?Pilot $pilot): self
    {
        $this->pilot = $pilot;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCouponDeadline(): ?DateTime
    {
        return $this->couponDeadline;
    }

    /**
     * @param DateTime|null $couponDeadline
     */
    public function setCouponDeadline(?DateTime $couponDeadline): void
    {
        $this->couponDeadline = $couponDeadline;
    }
}

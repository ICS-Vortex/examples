<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\CouponFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CouponFileRepository::class)
 * @ORM\Table(name="coupons_files")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class CouponFile implements Serializable
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin_coupons"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="couponFiles", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"admin_coupons"})
     */
    private $tournament;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin_coupons"})
     */
    private $source;
    /**
     * @Vich\UploadableField(mapping="tournaments_coupons_files", fileNameProperty="source")
     * @var File
     * @Ignore()
     * @Assert\File(
     *      mimeTypes = {
     *          "text/csv"
     *      }
     * )
     */
    private $sourceFile = null;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"admin_coupons"})
     */
    private $uploaded = false;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"admin_coupons"})
     */
    private $withErrors = false;

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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getUploaded(): ?bool
    {
        return $this->uploaded;
    }

    public function setUploaded(bool $uploaded): self
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    public function setSourceFile(File $file = null) : void
    {
        $this->sourceFile = $file;
        if ($file) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getSourceFile() : ?File
    {
        return $this->sourceFile;
    }

    public function getWithErrors(): ?bool
    {
        return $this->withErrors;
    }

    public function setWithErrors(bool $withErrors): self
    {
        $this->withErrors = $withErrors;

        return $this;
    }

    public function serialize()
    {
        return serialize($this->getId());
    }

    public function unserialize($serialized)
    {
        $this->id = unserialize($serialized);
    }
}

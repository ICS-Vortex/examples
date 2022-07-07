<?php

namespace App\Entity;

use App\Repository\ArticleTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="article_tags")
 * @ORM\Entity(repositoryClass=ArticleTagRepository::class)
 */
class ArticleTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("api_article_tags")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api_article_tags")
     */
    private $name;
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="tag", cascade={"persist"})
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }
    /**
     * @return ArrayCollection
     */
    public function getArticles(): ArrayCollection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): void
    {
        $this->articles->add($article);

    }

    public function removeArticle(Article $article): void
    {
        $this->articles->removeElement($article);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString() : string
    {
        return $this->name;
    }


}

<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ArticleCategory
 *
 * @ORM\Table(name="article_categories")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ArticleCategory
{
     use Timestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_articles"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Groups({"api_articles"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="titleEn", type="string", length=255)
     * @Groups({"api_articles"})
     */
    private $titleEn;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", options={"default": 1})
     */
    private $active = true;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
     */
    private $articles;


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
     * Set title
     *
     * @param string $title
     *
     * @return ArticleCategory
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titleEn
     *
     * @param string $titleEn
     *
     * @return ArticleCategory
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    /**
     * Get titleEn
     *
     * @return string
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return ArticleCategory
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * Add article
     *
     * @param Article $article
     *
     * @return ArticleCategory
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param Article $article
     */
    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
}

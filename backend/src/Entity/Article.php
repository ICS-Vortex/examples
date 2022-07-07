<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Includes\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 * @ApiResource()
 */
class Article
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
     * @var Server
     * @ORM\ManyToOne(targetEntity="Server")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_articles"})
     */
    public $server;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128)
     * @Groups({"api_articles"})
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(name="youtube_shortcode", type="string", length=255, nullable=true)
     * @Groups({"api_articles"})
     */
    private $youtubeShortCode;

    /**
     * @var string
     *
     * @ORM\Column(name="title_en", type="string", length=128)
     * @Groups({"api_articles"})
     */
    private $titleEn;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=256)
     * @Groups({"api_articles"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="description_en", type="string", length=256)
     * @Groups({"api_articles"})
     */
    private $descriptionEn;
    /**
     * @var string
     *
     * @ORM\Column(name="ru", type="text")
     * @Groups({"api_articles"})
     */
    private $ru;

    /**
     * @var string
     *
     * @ORM\Column(name="en", type="text")
     * @Groups({"api_articles"})
     */
    private $en;

    /**
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="isVideoPost", type="boolean", nullable=true)
     * @Groups({"api_articles"})
     */
    private $isVideoPost = false;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=128)
     * @Groups({"api_articles"})
     */
    private $metaTitle;
    /**
     * @var string
     *
     * @ORM\Column(name="meta_h1", type="string", length=128)
     * @Groups({"api_articles"})
     */
    private $metaH1;
    /**
     * @var string
     *
     * @ORM\Column(name="meta_keyword", type="string", length=256)
     * @Groups({"api_articles"})
     */
    private $metaKeyword;
    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text")
     * @Groups({"api_articles"})
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title_en", type="string", length=128)
     * @Groups({"api_articles"})
     */
    private $metaTitleEn;
    /**
     * @var string
     *
     * @ORM\Column(name="meta_h1_en", type="string", length=128)
     * @Groups({"api_articles"})
     */
    private $metaH1En;
    /**
     * @var string
     *
     * @ORM\Column(name="meta_keyword_en", type="string", length=256)
     * @Groups({"api_articles"})
     */
    private $metaKeywordEn;
    /**
     * @var string
     *
     * @ORM\Column(name="meta_description_en", type="text")
     * @Groups({"api_articles"})
     */
    private $metaDescriptionEn;
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=256)
     * @Groups({"api_articles"})
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="article_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var int
     *
     * @ORM\Column(name="views", type="integer", options={"default"= 0})
     * @Groups({"api_articles"})
     */
    private $views = 0;

    /**
     * @ORM\ManyToOne(targetEntity="ArticleCategory", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Groups({"api_articles"})
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="ArticleComment", mappedBy="article")
     * @Groups({"api_articles"})
     */
    private $comments;
    /**
     * @ORM\ManyToOne(targetEntity="ArticleTag", inversedBy="articles")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_articles"})
     */
    private $tag;

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
     * Set title
     *
     * @param string $title
     * @return Article
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
     * @return string
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * @param string $titleEn
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * @param string $descriptionEn
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;
    }

    /**
     * Set ru
     *
     * @param string $ru
     * @return Article
     */
    public function setRu($ru)
    {
        $this->ru = $ru;

        return $this;
    }

    /**
     * Get ru
     *
     * @return string
     */
    public function getRu()
    {
        return $this->ru;
    }

    /**
     * Set en
     *
     * @param string $en
     * @return Article
     */
    public function setEn($en)
    {
        $this->en = $en;

        return $this;
    }

    /**
     * Get en
     *
     * @return string
     */
    public function getEn()
    {
        return $this->en;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Article
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * @param string $metaTitle
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * @return string
     */
    public function getMetaH1()
    {
        return $this->metaH1;
    }

    /**
     * @param string $metaH1
     */
    public function setMetaH1($metaH1)
    {
        $this->metaH1 = $metaH1;
    }

    /**
     * @return string
     */
    public function getMetaKeyword()
    {
        return $this->metaKeyword;
    }

    /**
     * @param string $metaKeyword
     */
    public function setMetaKeyword($metaKeyword)
    {
        $this->metaKeyword = $metaKeyword;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string
     */
    public function getMetaTitleEn()
    {
        return $this->metaTitleEn;
    }

    /**
     * @param string $metaTitleEn
     */
    public function setMetaTitleEn($metaTitleEn)
    {
        $this->metaTitleEn = $metaTitleEn;
    }

    /**
     * @return string
     */
    public function getMetaH1En()
    {
        return $this->metaH1En;
    }

    /**
     * @param string $metaH1En
     */
    public function setMetaH1En($metaH1En)
    {
        $this->metaH1En = $metaH1En;
    }

    /**
     * @return string
     */
    public function getMetaKeywordEn()
    {
        return $this->metaKeywordEn;
    }

    /**
     * @param string $metaKeywordEn
     */
    public function setMetaKeywordEn($metaKeywordEn)
    {
        $this->metaKeywordEn = $metaKeywordEn;
    }

    /**
     * @return string
     */
    public function getMetaDescriptionEn()
    {
        return $this->metaDescriptionEn;
    }

    /**
     * @param string $metaDescriptionEn
     */
    public function setMetaDescriptionEn($metaDescriptionEn)
    {
        $this->metaDescriptionEn = $metaDescriptionEn;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return bool
     */
    public function isIsVideoPost()
    {
        return $this->isVideoPost;
    }

    /**
     * @param bool $isVideoPost
     */
    public function setIsVideoPost($isVideoPost)
    {
        $this->isVideoPost = $isVideoPost;
    }

    public function isPublic(){
        return $this->public;
    }
    /**
     * Get isVideoPost
     *
     * @return boolean
     */
    public function getIsVideoPost()
    {
        return $this->isVideoPost;
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }

    /**
     * Set category
     *
     * @param ArticleCategory $category
     *
     * @return Article
     */
    public function setCategory(ArticleCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return ArticleCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Add comment
     *
     * @param ArticleComment $comment
     *
     * @return Article
     */
    public function addComment(ArticleComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param ArticleComment $comment
     */
    public function removeComment(ArticleComment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function setImageFile(File $image = null) : void
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile() : ?File
    {
        return $this->imageFile;
    }

    /**
     * Set server
     *
     * @param Server|null $server
     * @return Article
     */
    public function setServer(Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return string
     */
    public function getYoutubeShortCode()
    {
        return $this->youtubeShortCode;
    }

    /**
     * @param string $youtubeShortCode
     */
    public function setYoutubeShortCode($youtubeShortCode)
    {
        $this->youtubeShortCode = $youtubeShortCode;
    }

    /**
     * @return ArticleTag
     */
    public function getTag(): ?ArticleTag
    {
        return $this->tag;
    }

    /**
     * @param ArticleTag $tag
     */
    public function setTag(ArticleTag $tag): void
    {
        $this->tag = $tag;
    }
}

<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ArticleComment
 *
 * @ORM\Table(name="article_comments")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleCommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ArticleComment
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_articles"})
     */
    private $author;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_articles"})
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_articles"})
     */
    private $phone;
    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     * @Groups({"api_articles"})
     */
    private $comment;
    /**
     * @var int
     *
     * @ORM\Column(name="likes", type="integer", options={"default": 0})
     * @Groups({"api_articles"})
     */
    private $likes = 0;

    private $recaptchaToken;

    /**
     * @var int
     *
     * @ORM\Column(name="dislikes", type="integer", options={"default": 0})
     * @Groups({"api_articles"})
     */
    private $dislikes = 0;
    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

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
     * Set comment
     *
     * @param string $comment
     *
     * @return ArticleComment
     */
    public function setComment($comment)
    {
        $this->comment = strip_tags($comment);

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set likes
     *
     * @param integer $likes
     *
     * @return ArticleComment
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes
     *
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set dislikes
     *
     * @param integer $dislikes
     *
     * @return ArticleComment
     */
    public function setDislikes($dislikes)
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    /**
     * Get dislikes
     *
     * @return int
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }

    /**
     * Set article
     *
     * @param Article $article
     *
     * @return ArticleComment
     */
    public function setArticle(Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getRecaptchaToken()
    {
        return $this->recaptchaToken;
    }

    /**
     * @param mixed $recaptchaToken
     */
    public function setRecaptchaToken($recaptchaToken): void
    {
        $this->recaptchaToken = $recaptchaToken;
    }
}

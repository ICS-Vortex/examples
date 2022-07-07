<?php

namespace App\Service;

use App\Entity\Article;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PDO;

class ArticlesService
{
    /** @var EntityManager $em */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $count
     * @return array|object[]
     */
    public function getLatestNews($count = 3)
    {
        $em = $this->em;
        return $em->getRepository(Article::class)->findBy(['public' => true], ['id' => 'DESC'], $count);
    }

    public function getArticleId(Article $article, $prev = true)
    {
        $em = $this->em;
        $table = $em->getClassMetadata(Article::class)->getTableName();
        if ($prev) {
            $query = "SELECT id FROM {$table} WHERE id < ? ORDER BY id DESC LIMIT 1";
        } else {
            $query = "SELECT id FROM {$table} WHERE id > ? ORDER BY id ASC LIMIT 1";

        }
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute([$article->getId()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($result) ? $result['id'] : null;
    }

}
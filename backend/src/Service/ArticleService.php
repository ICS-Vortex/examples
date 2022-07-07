<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Log;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Monolog\Logger;

class ArticleService
{
    /** @var EntityManager $em */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $count
     * @return array
     */
    public function getLatestNews(int $count = 3): array
    {
        $em = $this->em;
        return $em->getRepository(Article::class)->findBy(['public' => true], ['id' => 'DESC'], $count);
    }

    public function getArticleId(Article $article, $prev = true)
    {
        $em = $this->em;
        $conn = $em
            ->getConnection();

        $table = $em->getClassMetadata(Article::class)->getTableName();
        if ($prev) {
            $query = "SELECT id FROM {$table} WHERE id < ? ORDER BY id DESC LIMIT 1";
        } else {
            $query = "SELECT id FROM {$table} WHERE id > ? ORDER BY id ASC LIMIT 1";

        }

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $article->getId());
            $result = $stmt->executeQuery()->fetchAssociative();
            return !empty($result) ? intval($result['id']) : null;
        } catch (Exception $e) {
            $em->getRepository(Log::class)->log($e->getMessage() . ' | ' . $e->getTraceAsString(), Logger::CRITICAL);
            return null;
        }
    }

}

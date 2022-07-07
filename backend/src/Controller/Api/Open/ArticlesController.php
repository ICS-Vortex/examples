<?php

namespace App\Controller\Api\Open;

use App\Entity\Article;
use App\Entity\ArticleTag;
use App\Entity\FeaturedVideo;
use App\Entity\MissionRegistry;
use App\Entity\Server;
use App\Service\ArticleService;
use Doctrine\DBAL\Driver\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/list/{server}", name="api.open.articles.list")
     * @param SerializerInterface $serializer
     * @param Server|null $server
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function list(SerializerInterface $serializer, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }

        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['server' => $server]);
        $videos = $this->getDoctrine()->getRepository(FeaturedVideo::class)->findBy(['server' => $server]);
        $total = $this->getDoctrine()->getRepository(Article::class)->getArticlesCount($server);
        return $this->json([
            'articles' => $serializer->normalize($articles, 'json', ['groups' => 'api_articles']),
            'server' => $serializer->normalize($server, 'json', ['groups' => 'api_open_servers']),
            'videos' => $serializer->normalize($videos, 'json', ['groups' => 'api_featured_video']),
            'total' => $total,
            'limit' => 3,
        ]);
    }
    /**
     * @Route("/tags", name="api.open.articles.tags")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function tags(SerializerInterface $serializer): JsonResponse
    {
        $tags = $this->getDoctrine()->getRepository(ArticleTag::class)->findAll();
        return $this->json($serializer->normalize($tags, 'json', ['groups' => 'api_article_tags']));
    }

    /**
     * @Route("/get/{server}", name="api.open.articles.get_articles")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Server|null $server
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function getArticles(Request  $request, SerializerInterface $serializer, Server $server = null): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $options['s'] = $request->get('s');
        $options['tag'] = (int) $request->get('tag');
        $limit = 10;
        $offset = ($page - 1)  * $limit;
        $total = $this->getDoctrine()->getRepository(Article::class)->getArticlesCount($server, $options);

        $articles = $this->getDoctrine()->getRepository(Article::class)->getArticles($limit, $offset, $server, $options);

        return $this->json([
            'articles' => $serializer->normalize($articles, 'json', ['groups' => 'api_articles']),
            'total' => (int) $total
        ]);
    }

    /**
     * @Route("/{article}", name="api.open.articles.article")
     * @param ArticleService $articleService
     * @param SerializerInterface $serializer
     * @param Article|null $article
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function article(ArticleService $articleService, SerializerInterface $serializer, Article $article = null): JsonResponse
    {
        if (empty($article)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $article->setViews($article->getViews() + 1);
        $em->persist($article);
        $em->flush();
        $missionRegistry = $em->getRepository(MissionRegistry::class)->getLastMissionRegistry($article->getServer());

        return $this->json([
            'article' => $serializer->normalize($article, 'json', ['groups' => 'api_articles']),
            'server' => $serializer->normalize($article->getServer(), 'json', ['groups' => 'api_open_servers']),
            'online' => $serializer->normalize($article->getServer()?->getPilotsOnline(), 'json', ['groups' => 'api_open_servers']),
            'missionRegistry' => $serializer->normalize($missionRegistry, 'json', ['groups' => 'api_open_servers']),
            'previous' => $articleService->getArticleId($article),
            'next' => $articleService->getArticleId($article, false),
        ]);

    }


    /**
     * @Route("/latest/{limit}", name="api.open.articles.latest")
     * @param SerializerInterface $serializer
     * @param int $limit
     * @return JsonResponse
     */
    public function latest(SerializerInterface $serializer, int $limit): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Article::class)->getLatestNews($limit);
        return $this->json($serializer->normalize($articles, 'json', ['groups' => 'api_articles']));

    }
}
<?php

namespace App\Controller\Main;

use App\Entity\Article;
use App\Entity\ArticleComment;
use App\Form\ArticleCommentType;
use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/** @Route("/articles") */
class ArticlesController extends AbstractController
{
    /** @Route("/list", name="main.articles.list", options={"expose"=true}, methods={"GET"}) */
    public function list() : JsonResponse
    {
        return $this->json(
            $this->getDoctrine()
                ->getRepository(Article::class)
                ->findBy([], ['createdAt' => 'DESC'], 6)
        );
    }
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @Route("/{article}/show", name="main.articles.show", options={"expose"=true}, methods={"GET"})
     * @param ArticleService $articleService
     * @param Article|null $article
     * @return Response
     */
    public function show(ArticleService $articleService, Article $article = null) : Response
    {
        if ($article === null) {
            return $this->json([]);
        }
        $em = $this->getDoctrine()->getManager();
        $article->setViews($article->getViews() + 1);
        $em->persist($article);
        $em->flush();
        return $this->json([
            'article' => $article,
            'previous' => $articleService->getArticleId($article),
            'next' => $articleService->getArticleId($article, false),
        ]);
    }

    public function captchaIsValid($recaptcha) : bool
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'secret' => '6LfWE8oUAAAAAGym5t89lccVlR_MXLDTDyFtWvEo', 'response' =>$recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);
        if (empty($response)) {
            return false;
        }
        
        if (!isset($response['score'])) {
            return false;
        }

        if ($response['score'] < 0.5) {
            return false;
        }

        return true;
    }

    /**
     * @Route("/{article}/add-comment", name="main.articles.add_comment", options={"expose":true}, methods={"POST"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param Article|null $article
     * @return JsonResponse
     */
    public function addComment(SerializerInterface $serializer, Request $request, Article $article = null) : JsonResponse
    {
        if ($article === null) {
            return $this->json([]);
        }
        $comment = new ArticleComment();
        $form = $this->createForm(ArticleCommentType::class, $comment);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($this->captchaIsValid($comment->getRecaptchaToken())) {
                    $em = $this->getDoctrine()->getManager();
                    $comment->setArticle($article);
                    $em->persist($comment);
                    $em->flush();
                    return $this->json([
                        'status' => 0,
                        'message' => 'Your comment successfully added',
                        'comment' => $serializer->normalize($comment, 'json', ['groups' => 'api_articles']),
                    ]);
                }

                return $this->json([
                    'status' => 1,
                    'message' => 'You are probably a Robot!',
                ]);
            }

            return $this->json([
                'status' => 1,
                'message' => 'Form is not valid, please check comment data',
                'errors' => $form->getErrors(),
            ]);
        }

        return $this->json([
            'status' => 1,
            'message' => 'Form is not submitted',
        ]);
    }

    /**
     * @Route("/{article}/add-view", name="main.articles.add_view", methods={"POST"}, options={"expose"=true})
     * @param Article|null $article
     * @return JsonResponse
     */
    public function addView(Article $article = null) : JsonResponse
    {
        if ($article === null) {
            return $this->json([]);
        }
        $em = $this->getDoctrine()->getManager();
        $article->setViews($article->getViews() + 1);
        $em->persist($article);
        $em->flush();
        return $this->json([]);
    }
}
<?php

namespace App\Controller\Main;

use App\Entity\Feedback;
use App\Entity\Partner;
use App\Form\FeedbackType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/feedback")
 */
class FeedbackController extends AbstractController
{
    /**
     * @return Response
     * @Route("/index.html", name="main.feedback.index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('main/feedback/index.html.twig', [
            'partners' => $this->getDoctrine()->getRepository(Partner::class)->findAll(),
        ]);
    }

    /**
     * @Route("/send", name="main.feedback.send", methods={"POST"})
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function send(Request $request, TranslatorInterface $translator) : JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $data = $request->request->all();

        $form->submit($data);
        if($form->isSubmitted()){
            if($form->isValid()){
                try{
                    $em->persist($feedback);
                    $em->flush();
                    return $this->json(array(
                        'status' => 0,
                        'message' => $translator->trans('message.feedback_received')
                    ));
                }catch (Exception $e){
                    return $this->json(array(
                        'status' => 1,
                        'message' => $translator->trans('error.feedback_failed'),
                        'error' => $e->getMessage(),
                    ));
                }
            }
            return $this->json(array(
                'status' => 1,
                'message' => $translator->trans('error.invalid_data'),
                'data' => $form->getErrors(),
            ));
        }
        return $this->json([
            'status' => 1,
            'message' => $translator->trans('error.feedback_failed')
        ]);
    }
}
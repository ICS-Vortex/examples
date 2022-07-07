<?php


namespace App\Controller\Api;


use App\Entity\Pilot;
use App\Form\Api\SocialNetworkType;
use App\Form\RegistrationType;
use DateInterval;
use DateTime;
use FOS\RestBundle\Controller\Annotations as Rest;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class LoginController
 * @package App\Controller\Api
 * @Route("/api/login")
 */
class LoginController extends AbstractController
{
    /**
     * @Rest\Post("/social/{_locale}", name="api.login.social")
     * @param Request $request
     * @param JWTTokenManagerInterface $tokenManager
     * @param RefreshTokenManagerInterface $refreshTokenManager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function social(Request $request, JWTTokenManagerInterface $tokenManager, RefreshTokenManagerInterface $refreshTokenManager, TranslatorInterface $translator): JsonResponse
    {
        $user = new Pilot();
        $form = $this->createForm(SocialNetworkType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.invalid_input_data'),
                'errors' => $form->getErrors(true),
            ]);
        }
        $pilot = null;
        if (!empty($request->get('facebookId'))) {
            $pilot = $this->getDoctrine()->getRepository(Pilot::class)
                ->findOneBy(['facebookId' => $user->getFacebookId()]);
        }

        if (!empty($user->getUcid())) {
            $pilot = $this->getDoctrine()->getRepository(Pilot::class)
                ->findOneBy(['ucid' => $user->getUcid()]);
        }

        if (!empty($pilot)) {
            $accessToken = $tokenManager->create($pilot);
            $refreshToken = $refreshTokenManager->create();
            $refreshToken->setRefreshToken();
            $refreshToken = $refreshToken->getRefreshToken();
            // Delete all old tokens
            $this->getDoctrine()->getManager()
                ->createQuery('DELETE FROM Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken rt WHERE rt.username = :user')
                ->setParameter('user', $pilot->getCallsign())
                ->execute();
            $rt = new RefreshToken();
            $rt->setRefreshToken($refreshToken);
            $rt->setUsername($pilot->getCallsign());
            $date = new DateTime();

            $rt->setValid($date->add(new DateInterval('PT' . $this->getParameter('gesdinet_jwt_refresh_token.ttl') . 'S')));
            $this->getDoctrine()->getManager()->persist($rt);
            $this->getDoctrine()->getManager()->flush();

            return $this->json([
                'status' => 0,
                'token' => $accessToken,
                'refreshToken' => $refreshToken,
            ]);
        }

        return $this->json([
            'status' => 1,
            'message' => $translator->trans('message.account_not_found')
        ]);
    }

    /**
     * @Rest\Post("/{_locale}/validate", name="api.login.validate")
     * @param Request $request
     * @return JsonResponse
     */
    public function validate(Request $request): JsonResponse
    {
        $ucid = $request->headers->get('X-DCS-UCID');
        if (is_null($ucid)) {
            return $this->json([], 404);
        }

        $pilot = $this->getDoctrine()->getRepository(Pilot::class)->findOneBy([
            'ucid' => $ucid
        ]);

        if (empty($pilot)) {
            return $this->json([], 404);
        }
        $form = $this->render('api/registration/form.html.twig', [
            'form' => $this->createForm(RegistrationType::class, $pilot)->createView()
        ]);
        return $this->json([
            'form' => $form,
        ]);
    }
}

<?php

namespace App\Controller\Api\Open;

use App\Constant\Parameter;
use App\Entity\IpJail;
use App\Entity\IpRequestFail;
use App\Entity\Location\Region;
use App\Entity\Pilot;
use App\Entity\SystemLog;
use App\Entity\Tournament;
use App\Entity\TournamentCouponRequest;
use App\Helper\Helper;
use App\Message\EmailMessage;
use App\Repository\SystemLogRepository;
use App\Service\Google\GoogleSheetsService;
use DateTime;
use Exception;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/open/coupons")
 */
class CouponsController extends AbstractController
{
    /**
     * @Route("/receive", methods={"POST"}, name="api.open.tournament.coupons.receive")
     */
    public function receiveCoupon(
        MessageBusInterface $bus,
        GoogleSheetsService $sheetsService,
        Request             $request,
        TranslatorInterface $translator): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        /** @var IpJail $jail */
        if ($em->getRepository(IpJail::class)->clientIsInJail($request)) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.access_denied', [], null, $request->get('_locale')),
            ], Response::HTTP_FORBIDDEN);
        }
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->find(intval($request->get('tournament')));
        if (empty($tournament)) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.tournament_not_found', [], null, $request->get('_locale')),
            ], Response::HTTP_FORBIDDEN);
        }
        if (!$tournament->getProvideCoupons()) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.tournament_coupons_denied', [], null, $request->get('_locale')),
            ], Response::HTTP_FORBIDDEN);
        }
        $data = json_decode($request->getContent(), true);
        if (empty($data) || !isset($data['email']) || !isset($data['region']) || !isset($data['ucid'])) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.bad_request', [], null, $request->get('_locale')),
            ], Response::HTTP_BAD_REQUEST);
        }

        $region = $em->getRepository(Region::class)->find((int)$data['region']);
        if (empty($region)) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.invalid_region', [], null, $request->get('_locale')),
            ], Response::HTTP_BAD_REQUEST);
        }
        $email = $data['email'];
        if (!Helper::isValidEmail($email)) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.invalid_email', [], null, $request->get('_locale')),
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $em->getRepository(Pilot::class)->findOneBy([
            'ucid' => $data['ucid']
        ]);
        if (empty($user)) {
            $em->getRepository(IpRequestFail::class)->addFails($request);
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.account_not_found', [], null, $request->get('_locale')),
            ], Response::HTTP_FORBIDDEN);
        }
        /** @var TournamentCouponRequest $coupon */
        $coupon = $em->getRepository(TournamentCouponRequest::class)->findOneBy([
            'tournament' => $tournament,
            'pilot' => $user,
            'active' => true,
        ]);

        if (empty($coupon)) {
            $em->getRepository(IpRequestFail::class)->addFails($request);
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.coupons_not_found', [], null, $request->get('_locale')),
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$coupon->getActive()) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.coupon_already_accepted', [], null, $request->get('_locale')),
            ], Response::HTTP_BAD_REQUEST);
        }

        $coupon->setAcceptTime(new DateTime());
        $coupon->setActive(false);
        $user->setRegion($region);
        if (empty($user->getEmail())) {
            $user->setEmail($email);
            $user->setChecked(true);
            $user->setEnabled(true);
        }
        try {
            $em->persist($coupon);
            $em->persist($user);
            $em->flush();
            $sheetsService->setSheetId('1bTm4GblurseiHes9YpCGcjDMp_GRPZAfjD9se7H2O_k');
            $sheetsService->setTab('Coupons_List');
            $sheetsService->insert([[
                $user->getCallsign(),
                $user->getEmail(),
                $user->getRegion()->getTitleEn(),
                'No',
                $coupon->getCreatedAt()->format('Y-m-d H:i:s'),
                $coupon->getAcceptTime()->format('Y-m-d H:i:s'),
            ]]);
            $error = $sheetsService->getError();
            if ($error) {
                $email = new EmailMessage();
                $email->setRecipients(['spc.m1tek@gmail.com', 'vasyl@starsam.net']);
                $email->setSubject(Parameter::EMAIL_PREFIX . " Error sending coupon to {$user->getEmail()}");
                $message = sprintf("Error during sending coupon to  %s \n \n Error: %s", $user->getCallsign(), ($error['message']));
                $email->setBody($message);
                $bus->dispatch($email);
                $em->getRepository(SystemLog::class)->log($message, Logger::EMERGENCY, SystemLogRepository::INITIATOR_APPLICATION);
            }
            $em->getRepository(IpRequestFail::class)->clearFails($request->getClientIp());
            return $this->json([
                'status' => 0,
                'message' => $translator->trans('message.coupons_accept_success', [], null, $request->get('_locale')),
            ]);
        } catch (Exception $e) {
            $em->getRepository(SystemLog::class)->log($e->getMessage(), Logger::EMERGENCY, SystemLogRepository::INITIATOR_APPLICATION);
            $em->getRepository(SystemLog::class)->log($e->getTraceAsString(), Logger::EMERGENCY, SystemLogRepository::INITIATOR_APPLICATION);
            $email = new EmailMessage();
            $email->setRecipients(['spc.m1tek@gmail.com']);
            $email->setSubject(Parameter::EMAIL_PREFIX . " Ошибка получения купона");
            $message = sprintf("Произошла ошибка получения купона пользователем %s \n \n Код ошибки: %s", $user->getCallsign(), json_encode($e));
            $email->setBody($message);
            $bus->dispatch($email);
            $em->getRepository(SystemLog::class)->log($message, Logger::EMERGENCY, SystemLogRepository::INITIATOR_APPLICATION);
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.coupons_accept_failed', [], null, $request->get('_locale')) . ': shadowstrophy@gmail.com',
            ]);
        }
    }
}

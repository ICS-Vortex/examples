<?php

namespace App\Controller\Api\Coupons;

use App\Constant\Parameter;
use App\Entity\Location\Region;
use App\Entity\Pilot;
use App\Entity\SystemLog;
use App\Entity\TournamentCoupon;
use App\Entity\TournamentCouponRequest;
use App\Message\EmailMessage;
use App\Repository\SystemLogRepository;
use App\Service\Google\GoogleSheetsService;
use DateTime;
use Exception;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/{_locale}/coupons")
 */
class CouponsController extends AbstractController
{
    /**
     * @Route("/list", methods={"GET"}, name="api.coupons.list")
     */
    public function list(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $coupons = $this->getDoctrine()->getRepository(TournamentCoupon::class)->findBy(['pilot' => $user]);
        return $this->json($serializer->normalize($coupons, 'json', ['groups' => ['tournament_coupons']]));
    }

    /**
     * @Route("/{coupon}/accept", methods={"POST"}, name="api.coupons.accept")
     */
    public function accept(
        GoogleSheetsService     $sheetsService,
        MessageBusInterface     $bus,
        TranslatorInterface     $translator,
        Request                 $request,
        TournamentCouponRequest $coupon = null
    ): JsonResponse
    {
        /** @var Pilot $user */
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();
        if (empty($coupon)) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.invalid_coupon')
            ]);
        }
        if ($coupon->getPilot() !== $user) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.access_denied')
            ]);
        }
        if ($coupon->getActive() === false) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.invalid_coupon')
            ]);
        }
        if (empty($user->getEmail())) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.profile_empty_email')
            ]);
        }
        if (!empty($request->get('region'))) {
            $region = $manager->getRepository(Region::class)->find($request->get('region'));
            $user->setRegion($region);
            $manager->persist($user);
        }
        if (empty($user->getRegion())) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.profile_empty_region')
            ]);
        }
        $coupon->setActive(false);
        $coupon->setAcceptTime(new DateTime());
        try {
            $manager->persist($coupon);
            $manager->flush();
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
                $manager->getRepository(SystemLog::class)->log($message, Logger::EMERGENCY, SystemLogRepository::INITIATOR_APPLICATION);
            }
            return $this->json([
                'status' => 0,
                'message' => $translator->trans('message.coupons_accept_success'),
            ]);
        } catch (Exception $e) {
            $manager->getRepository(SystemLog::class)->log($e->getMessage(), Logger::EMERGENCY, SystemLogRepository::INITIATOR_APPLICATION);
            $manager->getRepository(SystemLog::class)->log($e->getTraceAsString(), Logger::EMERGENCY, SystemLogRepository::INITIATOR_APPLICATION);
            $email = new EmailMessage();
            $email->setRecipients(['spc.m1tek@gmail.com']);
            $email->setSubject(Parameter::EMAIL_PREFIX . " Ошибка получения купона");
            $message = sprintf("Произошла ошибка получения купона пользователем %s \n \n Код ошибки: %s", $user->getCallsign(), json_encode($e));
            $email->setBody($message);
            $bus->dispatch($email);
            $manager->getRepository(SystemLog::class)->log($message, Logger::EMERGENCY, SystemLogRepository::INITIATOR_APPLICATION);
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.coupons_accept_failed') . ': shadowstrophy@gmail.com',
            ]);
        }
    }
}

<?php

namespace App\Controller\Api\Open;

use App\Entity\GameDevice;
use App\Entity\Model\FinishRegistration;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\RegistrationTicket;
use App\Form\FinishRegistrationType;
use App\Repository\BaseUserRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/registration")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/confirm", name="api.open.registration.confirm", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     * @throws ExceptionInterface
     */
    public function confirm(Request $request, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);
        $token = trim(htmlspecialchars($data['token']));
        if (empty($token)) {
            return $this->json([
                'status' => 1,
                'message' => 'message.wrong_action'
            ], 400);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $ticket = $entityManager->getRepository(RegistrationTicket::class)
            ->findOneBy(['token' => $token]);
        if (!empty($ticket)) {
            if ($ticket->isAccepted()) {
                return $this->json([
                    'status' => 1,
                    'message' => 'message.registration_request_invalid'
                ]);
            }
            if ($ticket->isOutdated()) {
                return $this->json([
                    'status' => 1,
                    'message' => 'message.registration_request_outdated'
                ]);
            }
            /** @var Pilot $pilot */
            $pilot = $ticket->getPilot();

            return $this->json([
                'status' => 0,
                'pilot' => $serializer->normalize($pilot, 'json', ['groups' => 'api_open_servers']),
            ]);
        }

        return $this->json([
            'status' => 1,
            'message' => 'message.registration_request_not_found'
        ]);
    }

    /**
     * @Route("/finish", name="api.open.registration.finish", methods={"POST"})
     * @param Request $request
     * @param UserPasswordHasherInterface $encoder
     * @return Response
     * @throws Exception
     */
    public function finish(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $finishRegistration = new FinishRegistration();
        $form = $this->createForm(FinishRegistrationType::class, $finishRegistration);
        $form->submit($data);
        if (!$form->isSubmitted() || !$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return $this->json([
                'status' => 1,
                'message' => 'Bad request. ' . implode('|', $errors)
            ]);
        }
        $token = $finishRegistration->getToken();
        $password = $finishRegistration->getPassword();
        $repeatPassword = $finishRegistration->getRepeatPassword();
        $birthday = $finishRegistration->getBirthday();
        $name = $finishRegistration->getName();
        $surname = $finishRegistration->getSurname();
        $devices = (array) $data['devices'];
        if ($password !== $repeatPassword) {
            return $this->json([
                'status' => 1,
                'message' => 'error.passwords_do_not_match'
            ]);
        }
        /** @var RegistrationTicket $ticket */
        $ticket = $this->getDoctrine()->getManager()->getRepository(RegistrationTicket::class)
            ->findOneBy(['token' => $token]);
        if (!empty($ticket)) {
            if (!$ticket->isOutdated()) {
                $ticket->setAccepted(true);
                $pilot = $ticket->getPilot();
                foreach ($devices as $deviceId) {
                    $pilot->addDevice($em->getRepository(GameDevice::class)->findOneBy([
                        'id' => (int) $deviceId
                    ]));
                }
                if (!empty($birthday)) {
                    $pilot->setBirthday(new DateTime($birthday));
                }
                $pilot->setFavouritePlane($em->getRepository(Plane::class)->findOneBy([
                    'id' => (int) $finishRegistration->getFavouritePlane(),
                ]));
                $pilot->setRoles([BaseUserRepository::ROLE_USER]);
                $pilot->setEmail($ticket->getEmail());
                $pilot->setChecked(true);
                $pilot->setEnabled(true);
                $pilot->setName($name);
                $pilot->setSurname($surname);
                $password = $encoder->hashPassword($pilot, $password);
                $pilot->setPassword($password);
                $pilot->setPlainPassword($password);

                try{
                    $em->remove($ticket);
                    $em->persist($pilot);
                    $em->flush();
                    return $this->json([
                        'status' => 0,
                        'message' => 'message.registration_success',
                    ]);
                }catch (Exception $e) {
                    // TODO Add logging
                    return $this->json([
                        'status' => 1,
                        'message' => 'message.internal_server_error',
                    ]);
                }
            }
            $em->remove($ticket);
            $em->flush();
            return $this->json([
                'status' => 1,
                'message' => 'message.registration_request_outdated',
            ]);
        }

        return $this->json([
            'status' => 1,
            'message' => 'message.registration_request_not_found',
        ]);
    }
}

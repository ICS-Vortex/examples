<?php

namespace App\Controller\Api\Open;

use App\Entity\Log;
use App\Message\EmailMessage;
use App\Service\ApiAccessService;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/open/emails")
 */
class EmailsController extends AbstractController
{
    /**
     * @param Request $request
     * @param MessageBusInterface $bus
     * @param ApiAccessService $apiAccessService
     * @return JsonResponse
     * @Route("/send", name="api.open.emails.send")
     */
    public function send(Request $request, MessageBusInterface $bus, ApiAccessService $apiAccessService): JsonResponse
    {
        if (!$apiAccessService->isSerialNumberValid()) {
            return $this->json(['status' => 1, 'message' => 'Forbidden'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            return $this->json(['status' => 1, 'message' => 'Failed to send email. Invalid request']);
        }
        if (!isset($data['recipients'], $data['subject'], $data['body'])) {
            return $this->json(['status' => 1, 'message' => 'Failed to send email. Missing email data']);
        }
        $logRepo = $this->getDoctrine()->getRepository(Log::class);
        $logRepo->log('Sending email to ' . $data['recipients'], Logger::INFO, 'controller');

        $email = new EmailMessage();
        $email->setRecipients($this->trimRecipients($data['recipients']));
        $email->setSubject($data['subject']);
        $email->setBody($data['body']);
        $bus->dispatch($email);
        $logRepo->log('Email sent to queue', Logger::INFO, 'controller');

        return $this->json(['status' => 0, 'message' => 'Email sent']);
    }

    /**
     * @param $recipients
     * @return array
     */
    private function trimRecipients($recipients): array
    {
        $result = [];
        $recipients = explode(',', $recipients);
        $recipients = array_filter($recipients);
        foreach ($recipients as $recipient) {
            $result[] = trim($recipient);
        }
        return $result;
    }
}

<?php

namespace App\Controller;

use App\Entity\Server;
use App\Entity\Theatre;
use App\Entity\Visitor;
use App\Message\EmailMessage;
use App\Service\Google\GoogleSheetsService;
use DateTime;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Exception;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class TestController
 * @package App\Controller
 * @Route("/test")
 */
class TestController extends AbstractController
{
    /**
     * @return JsonResponse
     * @Route("/theatre")
     */
    public function theatre()
    {
        $theatre = $this->getDoctrine()->getRepository(Theatre::class)->findOneBy(['id' => 1]);
        $now = date('Y-m-d H:i:s', strtotime(' +1 day'));
        $today = date('Y-m-d', strtotime($now)) . ' ' . $theatre->getNightStart()->format('H:i:s');

        $tomorrow = date('Y-m-d', strtotime($now . ' +1 day')) . ' ' . $theatre->getNightEnd()->format('H:i:s');

        return $this->json([
            'start' => $theatre->getNightStart()->format('H:i:s'),
            'end' => $theatre->getNightEnd()->format('H:i:s'),
            'now' => $now,
            'today' => $today,
            'tomorrow' => $tomorrow,
            'isNightFlight' => ($now >= $today && $now <= $tomorrow),
        ]);
    }

    /**
     * @return JsonResponse
     * @Route("/index")
     */
    public function index()
    {
        try {
            $client = new Client(new Version2X('http://' . $this->getParameter('socketServerHost').':' . $this->getParameter('socketServerPort')));
            $client->initialize();
            $client->emit('send', [
                'room' => "server/1/chat",
                'message' => json_encode([
                    'username' => 'Test user',
                    'message' => 'I love you ALL!',
                    'time' => (new DateTime())->format('d.m.Y H:i:s')
                ])
            ]);
            $client->close();
            return $this->json(['data' => 'Ok']);
        } catch (Exception $e) {
            return $this->json(['data' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/email", name="test.email")
     * @param MessageBusInterface $bus
     * @return JsonResponse
     */
    public function email(MessageBusInterface $bus): JsonResponse
    {
        $email = new EmailMessage();
        $email->setSubject('Test');
        $email->setBody('test');
        $email->setRecipients(['vasyl@starsam.net']);
        $bus->dispatch($email);

        return $this->json([
            'message' => 0
        ]);
    }

    /**
     * @param MailerInterface $mailer
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws TransportExceptionInterface
     * @Route("/report", name="test.report")
     */
    public function report(MailerInterface $mailer): Response
    {
        $servers = $this->getDoctrine()->getRepository(Server::class)->findBy(['active' => true]);
        $spreadsheet = new Spreadsheet();

        foreach ($servers as $key => $server) {
            $data = $this->getDoctrine()->getRepository(Visitor::class)->getTodayVisitors($server);
            $sheet = $spreadsheet->createSheet($key);
            $sheet->setCellValue("A1", 'ID');
            $sheet->setCellValue("B1", 'Username');
            $sheet->setCellValue("C1", 'UCID');
            $sheet->setCellValue("D1", 'Address');
            $sheet->setCellValue("E1", 'Country');
            $sheet->setCellValue("F1", 'Visits');
            $sheet->setTitle($server->getName());
            foreach ($data as $index => $row) {
                $cell = $index += 2;
                $sheet->setCellValueExplicit("A{$cell}", $row['id'], DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("B{$cell}", str_replace('%', '', $row['username']), DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("C{$cell}", $row['ucid'], DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("D{$cell}", $row['ip_address'], DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("E{$cell}", strtoupper($row['country']), DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("F{$cell}", $row['visits'], DataType::TYPE_STRING);
            }
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'visitors_report_'.date('Y_m_d').'.xlsx';
        $path = $this->getParameter('kernel.project_dir') . '/public/uploads/reports';
        $fullPath = "{$path}/{$filename}";
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $writer->save($fullPath);

        $email = (new Email())
            ->from('virpilservers@gmail.com')
            ->to('vasyl@starsam.net')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->attachFromPath($fullPath, $filename)
        ;

        $mailer->send($email);
        unlink($fullPath);
        return $this->json([
            'sent' => 1
        ]);
    }

    /**
     * @Route("/google", name="test.google")
     */
    public function google(GoogleSheetsService $sheetsService)
    {
        $isSuccess = $sheetsService->setSheetId($this->getParameter('google.sheets.racing.id'))
            ->setTab($this->getParameter('google.sheets.racing.tab'))
            ->insert([
                ['Test1', 'Test2'],
                ['Test3', 'Test4'],
                ['Test5', 'Test6'],
                ['Test7', 'Test8'],
            ])->isSuccess();
        if ($isSuccess) {
            return $this->json(['status' => 'Data sent']);
        }
        return $this->json($sheetsService->getError());
    }

    /**
     * @Route("/render-email", name="test.render_email")
     */
    public function renderEmail()
    {
        return $this->render('emails/vpc/invite-en.html.twig', [
            'username' => 'test',
            'ucid' => '123456789abcdefghijklmnop',
            'tournamentTitle' => 'Shadow\'s Trophy',
            'tournamentId' => '1',
        ]);
    }

    /**
     * @Route("/render-email2", name="test.render_email2")
     */
    public function renderEmail2()
    {
        return $this->render('emails/email.html.twig', [
            'token' => 'test',
            'password' => 'test',
        ]);
    }
}

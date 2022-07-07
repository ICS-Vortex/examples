<?php

namespace App\MessageHandler;

use App\Message\DcsJsonMessage;
use App\Service\ParserService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ParserHandler implements MessageHandlerInterface
{
    private ParserService $parserService;

    /** @param ParserService $parserService */
    public function __construct(ParserService $parserService)
    {
        $this->parserService = $parserService;
    }

    public function __invoke(DcsJsonMessage $dcsJsonMessage)
    {
        $content = $dcsJsonMessage->getJson();
//        $this->println('Incoming JSON: '. $content);
        try{
            $result = $this->parserService->parse($content);
            $this->println(date('Y-m-d H:i:s'). ' - ' .$result['message']);
        } catch (\Exception $e) {
            $this->println('Message: ' . $e->getMessage());
            $this->println('File: ' . $e->getFile());
            $this->println('Line: ' . $e->getLine());
            $this->println('Trace: ' . $e->getTraceAsString());
        }
    }

    private function println(string $message) {
        echo $message . PHP_EOL;
    }
}

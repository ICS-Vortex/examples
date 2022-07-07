<?php

namespace App\Service\Google;

use Google\Service\Sheets\AppendValuesResponse;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleSheetsService
{
    private ParameterBagInterface $bag;
    private Google_Client $client;
    private Google_Service_Sheets $service;
    private ?string $tab;
    private ?string $sheetId;
    private ?array $error = [];
    private bool $success = false;

    private ?AppendValuesResponse $response;

    private array $insertParams = [
        'valueInputOption' => 'RAW',
    ];

    public function __construct(ParameterBagInterface $bag)
    {
        $this->bag = $bag;
        $this->init();
        $this->response = null;
    }

    /**
     * @return string
     */
    public function getTab(): string
    {
        return $this->tab;
    }

    /**
     * @param string $tab
     * @return GoogleSheetsService
     */
    public function setTab(string $tab): self
    {
        $this->tab = $tab;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSheetId(): ?string
    {
        return $this->sheetId;
    }

    /**
     * @param string|null $sheetId
     * @return GoogleSheetsService
     */
    public function setSheetId(?string $sheetId): self
    {
        $this->sheetId = $sheetId;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getError(): ?array
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    private function init()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Google Sheets and PHP');
        $this->client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $this->client->setAccessType('offline');
        $this->client->setAuthConfig($this->bag->get('kernel.project_dir') . '/sheets-credentials.json');
        $this->service = new Google_Service_Sheets($this->client);
    }

    /**
     * @param array $values
     * @return $this
     */
    public function insert(array $values) :self
    {
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        try{
            $this->response = $this->service->spreadsheets_values
                ->append($this->sheetId, $this->tab, $body, $this->insertParams);
            if ($this->response instanceof AppendValuesResponse) {
                $this->success = true;
            }
        } catch (\Exception $e) {
            $this->success = false;
            $this->error = json_decode($e->getMessage(), true)['error'] ?? null;
        }
        return $this;
    }

    /**
     * @return AppendValuesResponse|null
     */
    public function getResponse(): ?AppendValuesResponse
    {
        return $this->response;
    }
}

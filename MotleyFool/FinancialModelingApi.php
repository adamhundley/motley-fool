<?php
namespace MotleyFool;

use MotleyFool\Company;

class FinancialModelingApi
{
    const BASE_URL = 'https://financialmodelingprep.com/api/v3/company/';
    private $ticker;
    private $url;
    private $response;

    public function __construct()
    {
    }

    public function getResponse(): array
    {
        return wp_remote_get($this->url);
    }

    public function getBody(): object
    {
        return json_decode(wp_remote_retrieve_body($this->response));
    }

    public function getResponseCode(): string
    {
        return wp_remote_retrieve_response_code($this->response);
    }

    public function getCompany($ticker): ?object
    {
        $this->ticker = strtolower($ticker);
        $this->url = self::BASE_URL . "profile/$this->ticker";
        $this->response = $this->getResponse();
        if ($this->getResponseCode() == 200) {
            return new Company($this->getBody());
        }

        return null;
    }

    public function getCompanyList(): ?array
    {
        $this->url = self::BASE_URL . 'stock/list';
        $this->response = $this->getResponse();
        if ($this->getResponseCode() == 200) {
            return $this->getBody()->symbolsList;
        }

        return null;
    }
}

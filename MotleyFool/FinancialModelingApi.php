<?php
namespace MotleyFool;

use stdClass;
use MotleyFool\Company;

class FinancialModelingApi
{
    const BASE_URL = 'https://financialmodelingprep.com/api/v3/company/profile/';
    private $ticker;
    private $response;

    public function __construct($ticker)
    {
        $this->ticker = strtolower($ticker);
        $this->url = self::BASE_URL . $this->ticker;
        $this->response = $this->getResponse();
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

    public function getCompany(): ?object
    {
        if ($this->getResponseCode() == 200) {
            return new Company($this->getBody());
        }

        return null;
    }
}

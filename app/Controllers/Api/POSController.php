<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\POSService;
use App\Services\ReceiptService;

use CodeIgniter\API\ResponseTrait;

class POSController extends BaseController
{
    use ResponseTrait;

    protected $service;
    protected $receiptService;

    public function __construct()
    {
        $this->service = new POSService();
        $this->receiptService = new ReceiptService();
    }

    public function checkout()
    {
        $payload = $this->request->getJSON(true);

        try {
            $result = $this->service->checkout($payload);
            return $this->response->setJSON($result);
        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => $e->getMessage()]);
        }
    }

    public function calculateDiscount()
    {
        $payload = $this->request->getJSON(true);
        return $this->respond($this->service->calculate($payload));
    }

    public function receipt($trxId)
    {
        return $this->response->setJSON(
            $this->receiptService->getReceipt((int)$trxId)
        );
    }

}

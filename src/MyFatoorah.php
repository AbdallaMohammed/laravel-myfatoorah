<?php

namespace AymanElmalah\MyFatoorah;

use AymanElmalah\MyFatoorah\Services\Service;

class MyFatoorah extends Service
{
    /**
     * @var $payment
     */
    protected $payment;

    /**
     * Create invoice
     *
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createInvoice($data = [])
    {
        try {
            $this->endpoint = 'SendPayment';

            $response = $this->getClient()->post($this->getFullIUrl(), $data);
        } catch(\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        if(! $response->successful()) {
            throw new \Exception("Something went wrong");
        }

        return $response->json();
    }

    /**
     * Payment
     *
     * @param $payment_id
     * @return mixed
     * @throws \Exception
     */
    public function payment($payment_id)
    {
        try {
            $this->endpoint = 'GetPaymentStatus';

            $this->payment = $this->getClient()->post($this->getFullIUrl(), [
                'KeyType' => 'PaymentId',
                'Key' => $payment_id,
            ]);
        } catch(\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $this;
    }

    /**
     * Get payment
     *
     * @return mixed
     */
    public function get() {
        return $this->payment ? $this->payment->json() : null;
    }

    /**
     * Check that payment status is success
     *
     * @return bool
     * @throws \Exception
     */
    public function isSuccess()
    {
        $response = $this->payment;

        return (data_get($response, 'IsSuccess') != true || data_get($response, 'Data.InvoiceStatus') != 'Paid') ? false : true;
    }
}
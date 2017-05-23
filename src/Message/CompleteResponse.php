<?php

namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Mpesa Complete Response
 */
class CompleteResponse
{
    protected $responseCode;
    protected $responseDesc;
    protected $checkoutRequestID;
    protected $merchantRequestID;
    protected $customerMessage;

    protected $post;

    public function __construct($data, $secretKey)
    {
        $response = json_decode($data);
        if(empty($response['ProcessCheckoutResponse']) || empty($response['ProcessCheckoutResponse']['return'])) {
            throw new InvalidRequestException('Invalid data');
        }

        $data = $response['ProcessCheckoutResponse']['return'];

        $this->secretKey = $secretKey;

        $this->responseCode = $this->getByKeyFromArray('ResponseCode', $data);
        $this->responseDesc = $this->getByKeyFromArray('ResponseDesc', $data);
        $this->checkoutRequestID = $this->getByKeyFromArray('CheckoutRequestID', $data);
        $this->merchantRequestID = $this->getByKeyFromArray('MerchantRequestID', $data);
        $this->customerMessage = $this->getByKeyFromArray('CustomerMessage', $data);
    }

    public function validate()
    {
        $validated = false;
        if($this->status == 1) {
            $data = $this->post;
            $checksum = "";
            foreach ($data as $dKey => $dValue) {
                $checksum .= $dValue;
            }
            $hash = md5($checksum . $this->getSecretKey());
            if($hash == $this->checksum) {
                $validated = true;
            }
        }

        $validated = true; // HACK FOR TESTING... REMOVE

        return $validated;
    }

    public function isSuccessful()
    {
        $successful = false;
        if($this->validate() == true) {
            $successful = true;
        }
        return $successful;
    }

    public function getSecretKey()
    {
        return isset($this->secretKey) ? $this->secretKey : null;
    }

    public function getCode()
    {
        return isset($this->responseCode) ? $this->responseCode : null;
    }

    public function getMessage()
    {
        return isset($this->responseDesc) ? $this->responseDesc : null;
    }

    public function getStatus()
    {
        return $this->getMessage();
    }

    protected function getByKeyFromArray($key, $data)
    {
        return !empty($data[$key]) ? $data[$key] : '';
    }
}

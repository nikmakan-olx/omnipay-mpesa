<?php
namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Message\AbstractRequest;
use GuzzleHttp\Client;

/**
 * Mpesa Query Request
 */
class QueryRequest extends AbstractRequest
{
    // set on payment request
    public function getReference()
    {
        return $this->getParameter('CheckoutRequestID');
    }
    public function setReference($value)
    {
        return $this->setParameter('CheckoutRequestID', $value);
    }

    // from config
    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }
    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }


    public function getData()
    {
        $this->validate(
            'CheckoutRequestID'
        );

        $timestamp = date("Ymdhis");
        
        $data = [
            "QueryCheckout" => [
                "QueryCheckoutRequest" => [
                    "checkoutHeader" => [
                        "BusinessShortCode" => $this->getMerchantId(),
                        "Password" => $this->generateSignature($timestamp),
                        "Timestamp" => $timestamp
                    ],
                    "queryTransaction" => [
                        "CheckoutRequestID" => $this->getReference()
                    ]
			    ]
            ]
		];

        return $data;
    }

    public function generateSignature($timestamp)
    {
        return base64_encode($this->getMerchantId() . $this->getSecretKey() . $timestamp);
    }

    public function sendData($data)
    {
        $client = new Client();
        $httpResponse = $client->post($this->getEndpoint(), ['json' => $data]);
        $this->response = new QueryResponse($this, $httpResponse->getBody()->getContents(), $this->getTestMode());
        return $this->response;
    }

    public function getEndpoint()
    {
        return '{ ENTER ENDPOINT HERE !!! }';
    }
}

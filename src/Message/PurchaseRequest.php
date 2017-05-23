<?php
namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Message\AbstractRequest;
use GuzzleHttp\Client;

/**
 * Mpesa Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    // set on payment request
    public function getReference()
    {
        return $this->getParameter('reference');
    }
    public function setReference($value)
    {
        return $this->setParameter('reference', $value);
    }
    public function getAmount()
    {
        return $this->getParameter('amount');
    }
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }
    
    public function getUserEmail()
    {
        return $this->getParameter('userEmail');
    }
    public function setUserEmail($value)
    {
        return $this->setParameter('userEmail', $value);
    }
    public function getUserName()
    {
        return $this->getParameter('userName');
    }
    public function setUserName($value)
    {
        return $this->setParameter('userName', $value);
    }
    public function getUserId()
    {
        return $this->getParameter('userId');
    }
    public function setUserId($value)
    {
        return $this->setParameter('userId', $value);
    }
    public function getUserPhone()
    {
        return $this->getParameter('userPhone');
    }
    public function setUserPhone($value)
    {
        return $this->setParameter('userPhone', $value);
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


    public function getData()
    {
        $this->validate(
            'reference',
            'userEmail',
            'userId',
            'userPhone',
            'userName'
        );


        $data = [
            "ProcessCheckout" => [
                "ProcessCheckoutRequest" => [
                    "checkoutHeader" => [
                        "BusinessShortCode" => "898998",
                        "Password" => "ODk4OTk4NWU4ODQ4OThkYTI4MDQ3MTUxZDBlNTZmOGRjNjI5Mjc3MzYwM2QwZDZhYWJiZGQ2MmExMWVmNzIxZDE1NDJkODIwMTYwMjE2MTY1NjI3",
                        "Timestamp" => "20160912092847"
                    ],
                    "checkoutTransaction" => [
                        "SourceApp" => "olx-laduma",
                        "TransactionType" => "CustomerPayBillOnline",
                        "MerchantRequestID" => $this->getReference(),
                        "Amount" => $this->getAmount(),
                        "PhoneNumber" => $this->getUserPhone(),
                        "CallBackURL" => $this->getNotifyUrl(),
                        "Parameter" => [
                            "ReferenceItem" => [
                                [
                                    "Key" => "champ_lead",
                                    "Value" => "1"
                                ]
                            ]
                        ]
                    ],
                    "TransactionDesc" => "OLX Payment"
			    ]
            ]
		];

        return $data;
    }

    public function generateSignature($data)
    {
        $checksum = "";
        foreach ($data as $dKey => $dValue) {
            $checksum .= $dValue;
        }
        return md5($checksum . $this->getSecretKey());
    }

    public function sendData($data)
    {
        $client = new Client();
        $httpResponse = $client->post($this->getEndpoint(), ['json' => $data]);
        $this->response = new PurchaseResponse($this, $httpResponse->getBody()->getContents(), $this->getTestMode());
        return $this->response;
    }

    public function getEndpoint()
    {
        return null;
    }
}

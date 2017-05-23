<?php
namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Message\AbstractRequest;

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

        $data = [];
        $data['reference'] = $this->getReference();
        $data['amount'] = $this->getAmount();
        $data['userEmail'] = $this->getUserEmail();
        $data['userId'] = $this->getUserId();
        $data['userName'] = $this->getUserName();
        $data['userPhone'] = $this->getUserPhone();
        $data['checksum'] = $this->generateSignature($data);

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
        $this->response = new PurchaseResponse($this, http_build_query($data), $this->getTestMode());
        return $this->response;
    }

    public function getEndpoint()
    {
        return null;
    }
}

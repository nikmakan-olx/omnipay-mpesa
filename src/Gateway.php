<?php

namespace Omnipay\Mpesa;

use Omnipay\Common\AbstractGateway;
use Omnipay\Mpesa\Message\CompleteResponse;

/**
 * Mpesa Gateway
 *
 * @link TODO
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Mpesa';
    }

    public function getDefaultParameters()
    {
        return [
            'secretKey' => '',
            'merchantId' => '',
            'testMode' => false,
        ];
    }

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

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\PurchaseRequest', $parameters);
    }

    public function complete(array $parameters = array())
    {
        return new CompleteResponse($parameters, $this->getSecretKey());
    }

    public function query(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayGate\Message\QueryRequest', $parameters);
    }

}

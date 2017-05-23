<?php

namespace Omnipay\Mpesa;

use Omnipay\Common\AbstractGateway;

/**
 * OlxBranch Gateway
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

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\PurchaseRequest', $parameters);
    }

}

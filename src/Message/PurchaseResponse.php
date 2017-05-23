<?php

namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Mpesa Purchase Response
 */
class PurchaseResponse extends AbstractResponse
{
    public $testMode;

    public function __construct(RequestInterface $request, $data, $testMode = false)
    {
        $this->testMode = $testMode;
        parent::__construct($request, $data);
    }

    protected function getTestMode() {
        return $this->testMode;
    }

    protected function getProtocol() {
//        $protocol = (isset($_SERVER['HTTPS']) ? "https" : "http");
//        ^ this is not working, remove hack below wen issue is resolved
        $host = $_SERVER['HTTP_HOST'];
        $protocol = 'https';
        if(strpos($host, 'localhost') !== false ||
            strpos($host, 'mychamps') !== false) {
            $protocol = 'http';
        }
        return $protocol;
    }

    public function getRedirectUrl()
    {
        $url = $this->getProtocol() . "://$_SERVER[HTTP_HOST]";
        return $url . '/payment/mpesa';
    }

    public function isSuccessful()
    {
        return null;
    }
}

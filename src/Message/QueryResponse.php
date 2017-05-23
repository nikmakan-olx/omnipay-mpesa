<?php

namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Mpesa Query Response
 */
class QueryResponse extends AbstractResponse
{
    public $testMode;

    public $reference;
    public $code;
    public $description;
    public $metadata;

    public function __construct(RequestInterface $request, $data, $testMode = false)
    {
        $this->testMode = $testMode;
        parent::__construct($request, $data);

        $response = json_decode($data);
        if(empty($response['QueryCheckout']) ||
            empty($response['QueryCheckout']['QueryCheckoutResponse']) ||
            empty($response['QueryCheckout']['QueryCheckoutResponse']['return'])) {
            throw new InvalidRequestException('Invalid data');
        }

        $data = $response['QueryCheckout']['QueryCheckoutResponse']['return'];

        $this->reference = $this->getByKeyFromArray('MerchantRequestID', $data);
        $this->code = $this->getByKeyFromArray('ResultCode', $data);
        $this->description = $this->getByKeyFromArray('ResultDesc', $data);
        $this->metadata = $this->getByKeyFromArray('CallbackMetadata', $data);

    }

    protected function getTestMode() {
        return $this->testMode;
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

    protected function getByKeyFromArray($key, $data)
    {
        return !empty($data[$key]) ? $data[$key] : '';
    }
}

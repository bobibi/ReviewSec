<?php
namespace ReviewSec\Model\Entity\Log;

class Token
{

    public $Token;

    public $IPAddress;

    public $ExpireTime;

    public function exchangeArray($data)
    {
        $this->Token = (! empty($data['Token'])) ? $data['Token'] : null;
        $this->IPAddress = (! empty($data['IPAddress'])) ? $data['IPAddress'] : null;
        $this->ExpireTime = (! empty($data['ExpireTime'])) ? $data['ExpireTime'] : null;
    }
}
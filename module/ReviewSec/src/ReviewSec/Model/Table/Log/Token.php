<?php
namespace ReviewSec\Model\Table\Log;

use ReviewSec\Model\Table\TableBase;
use ReviewSec\Model\Entity\Log\Token as TokenEntity;

class Token extends TableBase {
    function insertRow(TokenEntity $row) {
        $this->tableGateway->insert(array(
            'Token' => $row->Token,
            'IPAddress' => $row->IPAddress,
            'ExpireTime' => $row->ExpireTime,
        ));
    }
}
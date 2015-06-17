<?php
namespace ReviewSec\Model\Table\Log;

use ReviewSec\Model\Table\TableBase;
use ReviewSec\Model\Entity\Log\Query as QueryEntity;

class Query extends TableBase
{
    public function insertRow(QueryEntity $row)
    {
        $this->tableGateway->insert(array(
            'Site' => $row->Site,
            'ProductID' => $row->ProductID,
            'IPAddress' => $row->IPAddress,
            'SourceURL' => $row->SourceURL,
        ));
    }
}
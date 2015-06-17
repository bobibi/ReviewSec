<?php
namespace ReviewSec\Model\Table\Log;

use ReviewSec\Model\Table\TableBase;
use ReviewSec\Model\Entity\Log\UnknowHTMLStructure as UnknowHTMLStructureEntity;

class UnknowHTMLStructure extends TableBase
{
    public function insertRow(UnknowHTMLStructureEntity $row) {
        $this->tableGateway->insert(array(
            'ProductID' => $row->ProductID,
            'Site' => $row->Site,            
            'Item' => $row->Item,
            'Field' => $row->Field,
            'Note' => $row->Note,
        ));
    }
}
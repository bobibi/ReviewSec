<?php
namespace ReviewSec\Model\Table\Amazon;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use ReviewSec\Model\Table\TableBase;
use ReviewSec\Model\Entity\Amazon\Review as ReviewEntity;

class Review extends TableBase
{

    public function getRowsByASIN($ASIN)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use($ASIN)
        {
            $select->where(array(
                'ASIN' => $ASIN
            ));
            $select->order('Date ASC');
        });
        return $rowset;
    }

    public function insertRow(ReviewEntity $row)
    {
        $this->tableGateway->insert(array(
            "ASIN" => $row->ASIN,
            "HelpfulVotes" => $row->HelpfulVotes,
            "Rating" => $row->Rating,
            "CustomerID" => $row->CustomerID,
            "TotalVotes" => $row->TotalVotes,
            "Date" => $row->Date,
            "Summary" => $row->Summary,
            "Content" => $row->Content,
            "Verified" => $row->Verified
        ));
    }
}
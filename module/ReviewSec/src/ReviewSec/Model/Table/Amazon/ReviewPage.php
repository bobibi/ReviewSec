<?php
namespace ReviewSec\Model\Table\Amazon;

use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use ReviewSec\Model\Table\TableBase;
use ReviewSec\Model\Entity\Amazon\ReviewPage as ReviewPageEntity;

class ReviewPage extends TableBase
{

    public function getRowByAllJointly($ASIN, $pageNumber, $token)
    {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array(
            'PageNumber'
        ));
        $sqlSelect->where(array(
            'ASIN' => $ASIN,
            'PageNumber' => $pageNumber,
            'AmazonReviewPage.Token' => $token
        ));
        $sqlSelect->join('Token', 'AmazonReviewPage.Token = Token.Token', array(
            "TokenExpired" => new Expression("Token.ExpireTime > NOW( )")
        ), 'left');
        
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet->current();
    }

    public function getRowsByASINJointly($ASIN)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use($ASIN)
        {
            $select->join('Token', 'Token.Token = AmazonReviewPage.Token', array(
                "TokenExpired" => new Expression("Token.ExpireTime > NOW( )")
            ), $select::JOIN_LEFT);
            $select->order('PageNumber DESC');
            $select->where(array(
                'ASIN' => $ASIN
            ));
        });
        return $resultSet;
    }

    public function getRowByASINPageNumber($ASIN, $pageNumber)
    {
        $rowset = $this->tableGateway->select(array(
            'ASIN' => $ASIN,
            'PageNumber' => $pageNumber
        ));
        return $rowset->current();
    }

    public function updateRowWithToken($ASIN, $PageNumber, $Token)
    {
        $this->tableGateway->update(array(
            'Token' => $Token
        ), array(
            'ASIN' => $ASIN,
            'PageNumber' => $PageNumber
        ));
    }

    public function insertRow(ReviewPageEntity $row)
    {
        $this->tableGateway->insert(array(
            "ASIN" => $row->ASIN,
            "PageNumber" => $row->PageNumber,
        ));
    }

    public function deleteRowByASINPageNumber($ASIN, $pageNumber)
    {
        $this->tableGateway->delete(array(
            'ASIN' => $ASIN,
            'PageNumber' => $pageNumber
        ));
    }
}
<?php
namespace ReviewSec\Model\Table\Amazon;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use ReviewSec\Model\Entity\Amazon\Product as ProductEntity;
use ReviewSec\Model\Table\TableBase;

class Product extends TableBase
{

    public function getRowByASIN($ASIN)
    {
        $rowset = $this->tableGateway->select(array(
            'ASIN' => $ASIN
        ));
        $row = $rowset->current();
        return $row;
    }

    public function getNumberOfAvailableReviewsByASIN($ASIN)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use($ASIN)
        {
            $select->join('AmazonReview', 'AmazonReview.ASIN = AmazonProduct.ASIN', array(), $select::JOIN_LEFT); // not necessarily to be left join, take efficiency into account in the future
            
            $select->columns(array(
                "NumberOfAvailableReviews" => new Expression("COUNT(*)")
            ));
            $select->where(array(
                'AmazonReview.ASIN' => $ASIN
            ));
        });
        $row = $resultSet->current();
        if (! $row) {
            return null;
        }
        return $row->NumberOfAvailableReviews;
    }

    public function insertRow(ProductEntity $row)
    {
        $this->tableGateway->insert(array(
            'ASIN' => $row->ASIN,
            'URL' => $row->URL,
            'AverageRating' => $row->AverageRating,
            'Name' => $row->Name,
            'ImageURL' => $row->ImageURL,
            'Price' => $row->Price,
            'Discount' => $row->Discount,
            'MerchantName' => $row->MerchantName,
            'MerchantURL' => $row->MerchantURL,
            'Category' => $row->Category,
            'Status' => $row->Status,
            'NumberOfReviews' => $row->NumberOfTotalReviews
        ));
    }

    public function updateRowByASIN($ASIN, ProductEntity $row)
    {
        $this->tableGateway->update(array(
            'URL' => $row->URL,
            'AverageRating' => $row->AverageRating,
            'Name' => $row->Name,
            'ImageURL' => $row->ImageURL,
            'Price' => $row->Price,
            'MerchantName' => $row->MerchantName,
            'MerchantURL' => $row->MerchantURL,
            'Category' => $row->Category,
            'Discount' => $row->Discount,
            'Status' => $row->Status,
            'NumberOfReviews' => $row->NumberOfTotalReviews
        ), array(
            "ASIN" => $ASIN
        ));
    }
}
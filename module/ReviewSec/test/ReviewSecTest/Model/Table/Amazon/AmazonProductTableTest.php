<?php
namespace ReviewSecTest\Model\Table\Amazon;

use ReviewSec\Model\Table\Amazon\Product as ProductTable;
use ReviewSec\Model\Entity\Amazon\Product as ProductEntity;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class AmazonProductTableTest extends PHPUnit_Framework_TestCase
{

    public function testCanInsertAProduct()
    {
        $productData = array(
            'ASIN' => 'UNIT0001',
            'URL' => 'http://unit.info',
            'AverageRating' => 4.3,
            'Name' => 'Unittest Product 0001',
            'ImageURL' => 'http://unit.info/image',
            'Price' => 99.99,
            'MerchantName' => 'UNITMerchant',
            'MerchantURL' => 'http://unit.info/merchant',
            'Category' => 'UNITCategory',
            'Status' => 'valid',
            'NumberOfTotalReviews' => 88
        );
        $product = new ProductEntity();
        $product->exchangeArray($productData);
        
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array(
            'insert'
        ), array(), '', false);
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($productData);
        
        $productTable = new ProductTable($mockTableGateway);
        $productTable->insertRow($product);
    }

    /*
    public function testCanUpdateAProduct()
    {
        $productData = array(
            'ASIN' => 'UNIT0001',
            'URL' => 'http://unit.info',
            'AverageRating' => 4.3,
            'Name' => 'Unittest Product 0001',
            'ImageURL' => 'http://unit.info/image',
            'Price' => 99.99,
            'MerchantName' => 'UNITMerchant',
            'MerchantURL' => 'http://unit.info/merchant',
            'Category' => 'UNITCategory',
            'Status' => 'valid',
            'NumberOfTotalReviews' => 88
        );
        $product = new ProductEntity();
        $product->exchangeArray($productData);
        
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new ProductEntity());
        $resultSet->initialize(array(
            $product
        ));
        
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array(
            'select',
            'update'
        ), array(), '', false);
        
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array(
            'ASIN' => 'UNIT0001'
        ))
            ->will($this->returnValue($resultSet));
        
        $mockTableGateway->expects($this->once())
            ->method('update')
            ->with(array(
            'URL' => 'http://unit.info',
            'AverageRating' => 4.3,
            'Name' => 'Unittest Product 0001',
            'ImageURL' => 'http://unit.info/image',
            'Price' => 99.99,
            'MerchantName' => 'UNITMerchant',
            'MerchantURL' => 'http://unit.info/merchant',
            'Category' => 'UNITCategory',
            'Status' => 'valid',
            'NumberOfTotalReviews' => 88
        ), array(
            'ASIN' => 'UNIT0001'
        ));
        
        $productTable = new ProductTable($mockTableGateway);
        $productTable->updateRowByASIN('UNIT0001', $product);
    }
    */
}
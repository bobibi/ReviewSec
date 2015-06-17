<?php
namespace ReviewSec\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use ReviewSec\Model\Entity\Amazon\Product as AmazonProductEntity;
use ReviewSec\Model\Table\Amazon\Product as AmazonProductTable;
use ReviewSec\Model\Entity\Amazon\Review as AmazonReviewEntity;
use ReviewSec\Model\Table\Amazon\Review as AmazonReviewTable;
use ReviewSec\Model\Entity\Amazon\ReviewPage as AmazonReviewPageEntity;
use ReviewSec\Model\Table\Amazon\ReviewPage as AmazonReviewPageTable;
use ReviewSec\Model\Entity\Log\UnknowHTMLStructure as UnknowHTMLStructureEntity;
use ReviewSec\Model\Table\Log\UnknowHTMLStructure as UnknowHTMLStructureTable;
use ReviewSec\Model\Entity\Log\Query as QueryEntity;
use ReviewSec\Model\Table\Log\Query as QueryTable;
use ReviewSec\Model\Entity\Log\Token as TokenEntity;
use ReviewSec\Model\Table\Log\Token as TokenTable;

class Database
{

    protected $dbAdapter;

    protected $amazonProductTable;

    protected $amazonReviewTable;
    
    protected $amazonReviewPageTable;
    
    protected $unknowHTMLStructureTable;
    
    protected $queryTable;
    
    protected $tokenTable;
    
    protected $logger;

    function __construct($sm)
    {
        $this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        $this->logger = $sm->get('ReviewSec\Logger');
    }
    
    function getTokenTable()
    {
        if (! $this->tokenTable) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new TokenEntity());
            $this->tokenTable = new TokenTable(new TableGateway('Token', $this->dbAdapter, null, $resultSetPrototype));
        }
    
        return $this->tokenTable;
    }
    
    function getQueryTable()
    {
        if (! $this->queryTable) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new QueryEntity());
            $tableGateway = new TableGateway('Query', $this->dbAdapter, null, $resultSetPrototype);
            $this->queryTable = new QueryTable($tableGateway);
        }
        
        return $this->queryTable;
    }
    
    function getUnknowHTMLStructureTable()
    {
        if (! $this->unknowHTMLStructureTable) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new UnknowHTMLStructureEntity());
            $tableGateway = new TableGateway('UnknowHTMLStructure', $this->dbAdapter, null, $resultSetPrototype);
            $this->unknowHTMLStructureTable = new UnknowHTMLStructureTable($tableGateway);
        }
    
        return $this->unknowHTMLStructureTable;
    }

    function getAmazonProductTable()
    {
        if (! $this->amazonProductTable) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new AmazonProductEntity());
            $tableGateway = new TableGateway('AmazonProduct', $this->dbAdapter, null, $resultSetPrototype);
            $this->amazonProductTable = new AmazonProductTable($tableGateway);
        }
        
        return $this->amazonProductTable;
    }

    function getAmazonReviewTable()
    {
        if (! $this->amazonReviewTable) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new AmazonReviewEntity());
            $this->amazonReviewTable = new AmazonReviewTable(new TableGateway('AmazonReview', $this->dbAdapter, null, $resultSetPrototype));
        }
        
        return $this->amazonReviewTable;
    }
    
    function getAmazonReviewPageTable()
    {
        if (! $this->amazonReviewPageTable) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new AmazonReviewPageEntity());
            $this->amazonReviewPageTable = new AmazonReviewPageTable(new TableGateway('AmazonReviewPage', $this->dbAdapter, null, $resultSetPrototype));
        }
    
        return $this->amazonReviewPageTable;
    }
}
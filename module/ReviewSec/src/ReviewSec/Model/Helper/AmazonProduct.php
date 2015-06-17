<?php 
namespace ReviewSec\Model\Helper;

use Zend\Db\Sql\Expression;
use ReviewSec\Model\Database;
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

class AmazonProduct{
    
    protected $database;
    
    protected $ASIN;
    
    protected $product;
    
    protected $reviewArray;
    
    protected $numberOfAvailableReviews;
    
    protected $logger;
    
    public function __construct($sm) {
        $this->database = $sm->get('ReviewSec\Model\Database');
        $this->logger = $sm->get('ReviewSec\Logger');
    }
    
    public function setASIN($ASIN){
        if (!empty($this->ASIN)) {
            throw new \Exception(__METHOD__." attempt to set an ASIN to a non-empty product helper");
        }
        $this->ASIN = $ASIN;
    }
    
    public function getProduct() {
        if (empty($this->ASIN)) {
            throw new \Exception(__METHOD__.", ASIN is not set");
        }
        if (!$this->product) {
            $this->product = $this->database->getAmazonProductTable()->getRowByASIN($this->ASIN);
        };
        
        return $this->product;
    }
    
    public function getNumberOfAvailableReviews() {
        if (empty($this->ASIN)) {
            throw new \Exception(__METHOD__.", ASIN is not set");
        }
        if (!$this->numberOfAvailableReviews) {
            $this->numberOfAvailableReviews = $this->database->getAmazonProductTable()->getNumberOfAvailableReviewsByASIN($this->ASIN);
        };
        
        return $this->numberOfAvailableReviews;
    }
    
    public function getReviewArray() {
        if (empty($this->ASIN)) {
            throw new \Exception(__METHOD__.", ASIN is not set");
        }
        if (!$this->reviewArray) {            
            $this->reviewArray = array();
            $rowSet = $this->database->getAmazonReviewTable()->getRowsByASIN($this->ASIN);
            foreach ($rowSet as $row) {
                $this->reviewArray[] = $row;
            }
        };
        
        return $this->reviewArray;
    }
    
    public function getLogger() {
        return $this->logger;
    }
}
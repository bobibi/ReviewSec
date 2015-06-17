<?php
namespace ReviewSec\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Expression;
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
use ReviewSec\Model\Helper\AmazonProduct as AmazonProductHelper;
use ReviewSec\Model\Helper\AmazonDetector;

class RestController extends AbstractRestfulController
{

    protected $database;

    protected $logger;

    public function getList()
    {
        return new JsonModel(array(
            'success' => 0,
            'message' => 'getList: undefined operation',
        ));
    }

    public function get($id)
    {
        return new JsonModel(array(
            'success' => 0,
            'message' => 'get: undefined operation',
        ));
    }
    
    // POST
    public function create($data)
    {
        $this->allowCrossDomain();
        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'feed_amazon_product':
                    return new JsonModel($this->feedAmazonProduct($data['data']));
                case 'report_unknown_html_structure':
                    return new JsonModel($this->reportUnknownHTMLStructure($data));
                case 'report_query':
                    return new JsonModel($this->reportQuery($data));
                case 'get_amazon_review_pages_to_crawl':
                    return new JsonModel($this->getAmazonReviewPages($data['ASIN']));
                case 'query_analysis':
                    return new JsonModel($this->queryAmazonDetector($data['ASIN']));
                case 'feed_amazon_review':
                    return new JsonModel($this->feedAmazonReview($data['data']));
            }
        }
        return new JsonModel($data);
    }

    public function update($id, $data)
    {
        return new JsonModel(array(
            'success' => 0,
            'message' => 'update: undefined operation',
        ));
    }

    public function delete($id)
    {
        return new JsonModel(array(
            'success' => 0,
            'message' => 'delete: undefined operation',
        ));
    }
    
    public function queryAmazonDetector($ASIN) {
        $logger = $this->getLogger();
        $logger->debug(__METHOD__);
        $response = array(
            'success' => 1,
        );
        try {
            $productHelper = new AmazonProductHelper($this->getServiceLocator());
            $productHelper->setASIN($ASIN);
        
            if (! $productHelper->getProduct()) {
                throw new \Exception("Product not available");
            }
            // TODO: log this query
            $productDetector = new AmazonDetector($productHelper);
            $productDetector->executeDetectors();
            $product = $productHelper->getProduct();
            $response['results'] = $productDetector->getResults();            
        } catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = $e->getMessage();
        }
        
        return $response;
    }
    
    public function getAmazonReviewPages($ASIN)
    {
        $logger = $this->getLogger();
        $logger->debug(__METHOD__);
        
        $pagesToAssign = 10;
        
        $response = array(
            'success' => 1,
            'pageQueue' => array(),
        );
        
        $db = $this->getDatabase();
        $reviewPageTable = $db->getAmazonReviewPageTable();

        try{
            $reviewPages = $reviewPageTable->getRowsByASINJointly($ASIN);
            foreach ($reviewPages as $page) {
                $response['pageQueue'][] = $page->PageNumber;
                $pagesToAssign --;
                if ($pagesToAssign <= 0)
                    break;
            }            	
        } catch(\Exception $e){
            $response['success'] = 0;
            $logger->err(__FUNCTION__.' get review page exception: '.$e->getMessage());
        }
        
        $logger->debug(__FUNCTION__.count($response['pageQueue']).' page tasks are assigned for '.$ASIN);
        return $response;
    }

    public function reportQuery($data)
    {
        $logger = $this->getLogger();
        $logger->debug(__METHOD__);
        
        $this->allowCrossDomain();
        
        $query = new QueryEntity();
        $logger->debug(__LINE__);
        $inputFilter = $query->getInputFilter();
        $logger->debug(__LINE__);
        $inputFilter->setData($data);
        $logger->debug(__LINE__);
        if ($inputFilter->isValid()) {
            $logger->debug(__LINE__);
            $query->exchangeArray($inputFilter->getValues());
            $query->IPAddress = $this->getRequest()->getServer('REMOTE_ADDR');
            $queryTable = $this->getDatabase()->getQueryTable();
            $logger->debug(__LINE__);
            try {
                $logger->debug(__LINE__);
                $queryTable->insertRow($query);
                $logger->debug(__LINE__);
            } catch (\Exception $e) {
                $logger->info(__METHOD__ . ' insert query exception: ' . $e->getMessage());
                return array(
                    'success' => 0
                );
            }
            return array('success' => 1);
        } else {
            $logger->info(__METHOD__ . ': input invalid');
            
            return array(
                'success' => 0,
                'message' => 'invalid input',
                'details' => $inputFilter->getMessages(),
            );
        }
    }

    public function reportUnknownHTMLStructure($data)
    {
        $logger = $this->getLogger();
        $logger->debug(__METHOD__);
        
        $this->allowCrossDomain();
        
        $report = new UnknowHTMLStructureEntity();
        $inputFilter = $report->getInputFilter();
        
        $inputFilter->setData($data);
        $response = array(
            'success' => 1
        );
        
        if ($inputFilter->isValid()) {
            $report->exchangeArray($inputFilter->getValues());
            $reportTable = $this->getDatabase()->getUnknowHTMLStructureTable();
            try {
                $reportTable->insertRow($report);
            } catch (\Exception $e) {
                $logger->info(__METHOD__ . ' insert report exception: ' . $e->getMessage());
                $response['success'] = 0;
            }
        } else {
            $logger->info(__METHOD__ . ': input invalid');
            $response['success'] = 0;
            $response['message'] = 'invalid input';
            $response['details'] = $inputFilter->getMessages();
        }
        
        return $response;
    }

    public function feedAmazonReview($data)
    {
        $logger = $this->getLogger();
        $logger->debug(__METHOD__);
        
        $this->allowCrossDomain();
        
        $review = new AmazonReviewEntity();
        $inputFilter = $review->getInputFilter();
        $error = [];
        $errorMessage = [];
        $pageFinished = false;
        $response = array(
            'success' => 1
        );
        
        try{
        foreach ($data['data'] as $onereview) {
            $inputFilter->setData($onereview);
            if ($inputFilter->isValid()) {
                $review->exchangeArray($inputFilter->getValues());
                $db = $this->getDatabase();
                $reviewTable = $db->getAmazonReviewTable();
                try {
                    $reviewTable->insertRow($review);
                    $pageFinished = true;
                } catch (\Exception $e) {
                    $logger->debug('insert review: '.$e->getMessage());
                }
                $error[] = false;
                $errorMessage[] = '';
            } else {
                $error[] = true;
                $errorMessage[] = $inputFilter->getMessages();
            }
        }
        
        if (isset($data['pageNumber']) && $pageFinished) {
            try {
                $db->getAmazonReviewPageTable()->deleteRowByASINPageNumber($data['ASIN'], $data['pageNumber']);
            } catch (\Exception $e) {
                $loger->debug('delete review page: '.$e->getMessage());
            }
        }
        $response['error'] = $error;
        $response['errorMessage'] = $errorMessage;
        } catch (\Exception $e) {
            $response['detail'] = $e->getMessage();
            $response['data'] = $data['data'];
        }
        
        return $response;
    }
    
    public function feedAmazonProduct($data)
    {
        $logger = $this->getLogger();
        $logger->debug(__METHOD__);
        
        $this->allowCrossDomain();
        
        $product = new AmazonProductEntity();
        $inputFilter = $product->getInputFilter();
        
        $inputFilter->setData($data);
        $error = false;
        $response = array(
            'success' => 1
        );
        
        if ($inputFilter->isValid()) {
            
            $product->exchangeArray($inputFilter->getValues());
            $db = $this->getDatabase();
            $productTable = $db->getAmazonProductTable();
            $reviewPageTable = $db->getAmazonReviewPageTable();
            
            $productTable->beginTransaction();
            try {
                $oldProduct = $productTable->getRowByASIN($product->ASIN);
                if (! $oldProduct) {
                    $product->Status = 'valid';
                    $productTable->insertRow($product);
                    $startPage = 1;
                    $oldNumberOfTotalReviews = 0;
                    $logger->debug(__FUNCTION__ . ': insert one product ' . $product->ASIN);
                } else {
                    $productTable->updateRowByASIN($product->ASIN, $product);
                    $startPage = floor($oldProduct->NumberOfTotalReviews / 10) + 1;
                    $oldNumberOfTotalReviews = $oldProduct->NumberOfTotalReviews;
                    $logger->debug(__FUNCTION__ . ': update one product ' . $product->ASIN);
                }
                $productTable->commit();
            } catch (\Exception $e) {
                $productTable->rollback();
                $logger->err(__FUNCTION__ . ': insert/update product exception: ' . $e->getMessage());
                $error = true;
                $response['success'] = 0;
                $response['message'] = 'insert/update product exception';
            }
//             $logger->debug(print_r($error, true));
//             $logger->debug(print_r($product->NumberOfTotalReviews, true));
//             $logger->debug(print_r($oldNumberOfTotalReviews, true));
            if (! $error && $product->NumberOfTotalReviews > $oldNumberOfTotalReviews) {
                $logger->debug('start insert review page');
                $newNumberOfReviewPages = ceil($product->NumberOfTotalReviews / 10);
                $reviewPage = new AmazonReviewPageEntity();
                $miss = 0;
                for ($i = $startPage; $i <= $newNumberOfReviewPages; $i ++) {
                    try {
                        $reviewPage->exchangeArray(array(
                            'ASIN' => $product->ASIN,
                            'PageNumber' => $i
                        ));
                        $reviewPageTable->insertRow($reviewPage);
                    } catch (\Exception $e) {
                        $miss ++;
                        $logger->alert(__METHOD__ . ': insert review page exception (this could happen when the last page has not been crawled): ' . $e->getMessage());
                    }
                }
                $logger->debug(__FUNCTION__ . ' insert ' . ($i - $startPage - $miss) . ' pages for ' . $product->ASIN);
            }
        } else {
            $logger->info(__METHOD__ . ': input invalid');
            $response['success'] = 0;
            $response['message'] = 'invalid input';
            $response['details'] = $inputFilter->getMessages();
        }
        
        return $response;
    }

    function allowCrossDomain()
    {
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Access-Control-Allow-Origin', '*');
    }

    public function getLogger()
    {
        if (! $this->logger) {
            $this->logger = $this->getServiceLocator()->get('ReviewSec\Logger');
        }
        return $this->logger;
    }

    public function getDatabase()
    {
        if (! $this->database) {
            $this->database = $this->getServiceLocator()->get('ReviewSec\Model\Database');
        }
        return $this->database;
    }
}
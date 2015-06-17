<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ReviewSec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ReviewSec\Form\QueryForm;
use ReviewSec\Form\Filter\QueryForm as QueryFormFilter;
use ReviewSec\Model\Helper\AmazonProduct as AmazonProductHelper;
use ReviewSec\Model\Helper\AmazonDetector;

class WebController extends AbstractActionController {
	public function indexAction() {
		$form = new QueryForm ();
		return new ViewModel(array(
            'form' => $form,
        ));
    }
    public function todoAction() {
    	$viewModel = new ViewModel();
    	$viewModel->setTerminal(true);
    	return $viewModel;
    }
    public function trackingAction() {
    	$viewModel = new ViewModel();
    	$viewModel->setTerminal(true);
    	return $viewModel;
    }
	public function aboutAction() {
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);
		return $viewModel;
	}
    
    public function queryAction()
    {
        $form = new QueryForm();    
        $request = $this->getRequest();
        $errorMessage = null;
        $product = null;
        $results = null;
        // form has been submitted
        if ($request->isPost()) {
            $formFilter = new QueryFormFilter();
            $form->setInputFilter ( $formFilter->getInputFilter () );
            $form->setData ( $request->getPost () );
            if ($form->isValid ()) {
                $formData = $form->getData();
                $site = $formData["Site"];
                $productID = $formData["ProductID"];
                
                try {
                    $productHelper = new AmazonProductHelper($this->getServiceLocator());
                    $productHelper->setASIN($productID);
                    
                    if (! $productHelper->getProduct()) {
                        throw new \Exception("Product not available");
                        //$productHelper->crawlProduct(); // -- it will wait here until the crawling is done or timeout
                    }
                    // TODO: log this query
                    $productDetector = new AmazonDetector($productHelper);
                    $productDetector->executeDetectors();
                    $product = $productHelper->getProduct();
                    $results = $productDetector->getResults();
                } catch (\Exception $e) {
                    $errorMessage = $e->getMessage();
                }
            }
        }
    
        return new ViewModel(array(
            'form' => $form,
            'results' => $results,
            'errorMessage' => $errorMessage,
            'product' => $product,
        ));
    }
}

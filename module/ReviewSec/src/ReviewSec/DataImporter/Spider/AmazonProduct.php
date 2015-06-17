<?php 
namespace ReviewSec\DataImporter\Spider;

class AmazonProduct {
        
    public function startCrawler($ASIN, $config) {
            $client = new Client();
    		$client->setUri($config["spider_url"]);
    		$client->setMethod('POST');
    		$client->setParameterPost(array(
    				'project' => $config['spider_project'],
    				'spider' => $config['product_spider'],
    				'asin' =>$ASIN,
    		));
    
    		$spiderResponse = $client->send();
    
    		if (!$spiderResponse->isSuccess()) {//TODO: read response
    			throw new \Exception(__METHOD__." Call spider failed");
    		}
    }
}
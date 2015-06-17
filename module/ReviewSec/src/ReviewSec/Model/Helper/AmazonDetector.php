<?php 
namespace ReviewSec\Model\Helper;

use ReviewSec\Model\Helper\AmazonProduct as ProductHelper;
use ReviewSec\Algorithm\AmazonCoolingTimeDetector;

class AmazonDetector {
    
    protected $prodHelper;
    
    protected $detectorArray = array();
    
    public function __construct(ProductHelper $prodHelper) {
        $this->prodHelper = $prodHelper;
        $this->detectorArray[] = new AmazonCoolingTimeDetector($prodHelper);
    }
    
    public function executeDetectors() {
        foreach ($this->detectorArray as $detector) {
            $detector->execute();
        }
    }
    
    public function getResults() {
        $ret = array();
        
        foreach ($this->detectorArray as $detector) {
            $ret[] = array(
                'tag' => $detector->getTag(),
                'malicious' => $detector->getMalicious(),
                'confidence' => $detector->getConfidence(),
            );
        }
        
        return $ret;
    }
}
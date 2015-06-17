<?php
namespace ReviewSec\Algorithm;

use ReviewSec\Model\Helper\AmazonProduct as ProductHelper;

abstract class AmazonDetectorBase {
    
    protected $prodHelper;
    
    protected $malicious = null;
    
    protected $confidence = null;
    
    public function __construct(ProductHelper $prodHelper) {
        $this->prodHelper = $prodHelper;
    }
    
    public function getMalicious() {
        if (is_null($this->malicious)) {
            throw new \Exception(__METHOD__." run execute(0 first");
        }
        return $this->malicious;
    }
    
    public function getConfidence() {
        if (is_null($this->malicious)) {
            throw new \Exception(__METHOD__." run execute(0 first");
        }
        return $this->confidence;
    }
    
    abstract public function getTag();
    
    abstract public function execute();
}
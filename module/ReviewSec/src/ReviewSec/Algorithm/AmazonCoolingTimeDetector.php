<?php
namespace ReviewSec\Algorithm;

use ReviewSec\Algorithm\AmazonDetectorBase;
use ReviewSec\Math\Gaussian;
use ReviewSec\Math\Vector;
use ReviewSec\Math\Statistics;

class AmazonCoolingTimeDetector extends AmazonDetectorBase {
        
    public function execute() {
        $reviewArray = $this->prodHelper->getReviewArray();
        $numOfAvailableReviews = $this->prodHelper->getNumberOfAvailableReviews();
        if($numOfAvailableReviews < 2) {
            $this->malicious = false;
            $this->confidence = 0;
            return;
        }
        
        $ratingArray = array_fill(0, $numOfAvailableReviews, null);
        $dateArray = array_fill(0, $numOfAvailableReviews, null);
        
        foreach ($reviewArray as $i => $review){
            $ratingArray[$i] = $review->Rating;
            $dateArray[$i] = strtotime($review->Date);
        }
        
        $vector = new Vector();
        $coolingTimeArray = $vector->diff($dateArray);
        $stat = new Statistics();
        $corrcoef = $stat->corrcoef($ratingArray, $coolingTimeArray);
        /**
p_low = normcdf(-conf*model_s, rho-model_mu, sqrt(s^2+model_s^2));
p_up = normcdf(conf*model_s, rho-model_mu, sqrt(s^2+model_s^2));

fit_level = p_up - p_low;
fitted = abs(rho-model_mu) < conf*model_s;
         * 
         */
        if(is_null($corrcoef['rho'])) {
            $this->malicious = false;
            $this->confidence = 0;
            return;
        }
        $rho = $corrcoef['rho'];
        $s = $corrcoef['std'];
        $this->prodHelper->getLogger()->debug($rho);
        $this->prodHelper->getLogger()->debug($s);

        $model_s = 0.0767;
        $model_mu = 0.0232;
        $conf = 2;
        $g = new Gaussian();
        $p_low = $g->cdf(-$conf*$model_s, $rho-$model_mu, sqrt($s*$s+$model_s*$model_s));
        $p_up = $g->cdf($conf*$model_s, $rho-$model_mu, sqrt($s*$s+$model_s*$model_s));
        
        $this->prodHelper->getLogger()->debug(-$conf*$model_s);
        $this->prodHelper->getLogger()->debug($rho-$model_mu);
        $this->prodHelper->getLogger()->debug(sqrt($s*$s+$model_s*$model_s));
        
        $this->malicious = abs($rho-$model_mu) > $conf*$model_s;
        if ($this->malicious) {
            $this->confidence = 100 - round(($p_up - $p_low)*100);
        } else {
            $this->confidence = round(($p_up - $p_low)*100);
        }
        
    }
    
    public function getTag() {
        return 'Cooling Time';
    }
}
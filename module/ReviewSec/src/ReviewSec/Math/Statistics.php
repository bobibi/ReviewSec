<?php 
namespace ReviewSec\Math;

class Statistics {
    function mean(array $x) {
        $mu = 0;
        $N = count($x);
        foreach ($x as $xi) {
            $mu += $xi/$N;
        }
        
        return $mu;
    }
    
    function variance(array $x){
        $ss = 0;
        $N = count($x);
        if ($N <= 1) {
            return 0;
        }
        $mu = $this->mean($x);
        foreach ($x as $xi) {
            $ss += ($xi-$mu)*($xi-$mu)/($N-1);
        }
        
        return $ss;
    }
    
    function cov(array $x, array $y) {
        $N = count($x);
        if (count($y) != $N) {
            throw new \Exception(__METHOD__." requires arrays with the same dimension");
        }
        if ($N <= 1) {
            return 0;
        }
        $mux = $this->mean($x);
        $muy = $this->mean($y);
        
        $c = 0;
        for($i = 0; $i < $N; $i++){
            $c += ($x[$i]-$mux)/($N-1)*($y[$i]-$muy);
        }
        
        return $c;
    }
    
    function corrcoef(array $x, array $y) {
        $N = count($x);
        if($N<=1) return array(
            'rho' => null,
            'std' => null,
        );
        $rho = $this->cov($x, $y) / sqrt($this->variance($x)) / sqrt($this->variance($y));
        $rho2 = $rho*$rho;
        $ss = (1-$rho2)*(1-$rho2)/($N-1)*(1+11*$rho2/2/$N);
        $std = pow($ss, 0.5);
        
        return array(
            'rho' => $rho,
            'std' => $std,
        );
    }
}
<?php 
namespace ReviewSec\Math;

class Vector {
    function diff(array $x) {
        $N = count($x);
        $y = array_fill(0, $N, 0);
        
        for ($i = 1; $i < $N; $i++) {
            $y[$i] = $x[$i] - $x[$i-1];
        }
        
        return $y;
    }
}
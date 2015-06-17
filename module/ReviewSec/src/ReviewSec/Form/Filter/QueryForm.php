<?php
/**
 * 
 * @author yongbo zeng
 * @email bo.li.zeng@gmail.com
 * @date July 9 2014
 * 
 * @detail this is the form definition for regular user interface
 *
 */
namespace ReviewSec\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class QueryForm implements InputFilterAwareInterface
{
    
    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception(__METHOD__ . " not used");
    }

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $this->inputFilter = new InputFilter();
            // product id
            $this->inputFilter->add(array(
                'name' => 'ProductID',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4, // ASIN for amazon is 10 charactors
                            'max' => 10 // These two number should be changed according to supported sites
                                                )
                    )
                )
            ));
            // site name
            $this->inputFilter->add(array(
                'name' => 'Site',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4, // yelp, amazon, ...
                            'max' => 10 // These two number should be changed according to supported sites
                                                )
                    )
                )
            ));
        }
        return $this->inputFilter;
    }
}

















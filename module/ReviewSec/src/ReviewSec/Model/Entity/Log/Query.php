<?php
namespace ReviewSec\Model\Entity\Log;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Query
{

    public $ID;

    public $Site;

    public $ProductID;
    
    public $SourceURL;

    public $IPAddress;

    public $Time;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->Site = (! empty($data['Site'])) ? $data['Site'] : null;
        $this->ProductID = (! empty($data['ProductID'])) ? $data['ProductID'] : null;
        $this->ID = (! empty($data['ID'])) ? $data['ID'] : null;
        $this->IPAddress = (! empty($data['IPAddress'])) ? $data['IPAddress'] : null;
        $this->Time = (! empty($data['Time'])) ? $data['Time'] : null;
        $this->SourceURL = (! empty($data['SourceURL'])) ? $data['SourceURL'] : null;
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception(__METHOD__ . ' not intend to be used');
    }
    
    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
    
            // Site
            $inputFilter->add(array(
                'name' => 'Site',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StringToLower'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^\w{1,20}$/'
                        )
                    )
                )
            ));
    
            // ProductID
            $inputFilter->add(array(
                'name' => 'ProductID',
                'required' => false,
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
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^\w{1,20}$/'
                        )
                    )
                )
            ));
    
            // SourceURL
            $inputFilter->add(array(
                'name' => 'SourceURL',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StringToLower'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 2048,
                        )
                    )
                )
            ));
    
            $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
}
<?php
namespace ReviewSec\Model\Entity\Log;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UnknowHTMLStructure
{

    public $Site;

    public $ProductID;

    public $Item;

    public $Field;

    public $Note;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->Site = (! empty($data['Site'])) ? $data['Site'] : null;
        $this->ProductID = (! empty($data['ProductID'])) ? $data['ProductID'] : null;
        $this->Item = (! empty($data['Item'])) ? $data['Item'] : null;
        $this->Field = (! empty($data['Field'])) ? $data['Field'] : null;
        $this->Note = (! empty($data['Note'])) ? $data['Note'] : null;
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
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^\w{1,20}$/'
                        )
                    )
                )
            ));
            
            // Item
            $inputFilter->add(array(
                'name' => 'Item',
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
            
            // Field
            $inputFilter->add(array(
                'name' => 'Field',
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
            
            // Note
            $inputFilter->add(array(
                'name' => 'Note',
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
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 0, // might change
                            'max' => 200
                        )
                    )
                )
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}
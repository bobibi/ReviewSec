<?php
namespace ReviewSec\Model\Entity\Amazon;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Review
{

    public $ASIN;

    public $HelpfulVotes;

    public $Rating;

    public $CustomerID;

    public $TotalVotes;

    public $Date;

    public $Summary;

    public $Content;

    public $Verified;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->ASIN = (! empty($data['ASIN'])) ? $data['ASIN'] : null;
        $this->HelpfulVotes = (! empty($data['HelpfulVotes'])) ? $data['HelpfulVotes'] : null;
        $this->Rating = (! empty($data['Rating'])) ? $data['Rating'] : null;
        $this->CustomerID = (! empty($data['CustomerID'])) ? $data['CustomerID'] : null;
        $this->TotalVotes = (! empty($data['TotalVotes'])) ? $data['TotalVotes'] : null;
        $this->Date = (! empty($data['Date'])) ? $data['Date'] : null;
        $this->Summary = (! empty($data['Summary'])) ? $data['Summary'] : null;
        $this->Content = (! empty($data['Content'])) ? $data['Content'] : null;
        $this->Verified = (! empty($data['Verified'])) ? $data['Verified'] : null;
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception(__METHOD__ . ' not intend to be used');
    }
    
    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
    
            // ASIN
            $inputFilter->add(array(
                'name' => 'ASIN',
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
                            'pattern' => '/^[A-Z0-9]{3,10}$/'
                        )
                    )
                )
            ));
            
            // HelpfulVotes
            $inputFilter->add(array(
                'name' => 'HelpfulVotes',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'Int'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 0,
                            'max' => 9999999 // any update?
                        )
                    )
                )
            ));
            
            // Rating
            $inputFilter->add(array(
                'name' => 'Rating',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'Int'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 1,
                            'max' => 5 // any update?
                        )
                    )
                )
            ));
            
            // CustomerID
            $inputFilter->add(array(
                'name' => 'CustomerID',
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
                            'min' => 3, // might change
                            'max' => 30,
                        )
                    )
                )
            ));
            
            // TotalVotes
            $inputFilter->add(array(
                'name' => 'TotalVotes',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'Int'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 0,
                            'max' => 9999999 // any update?
                        )
                    )
                )
            ));
            
            // Date
            $inputFilter->add(array(
                'name' => 'Date',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'Date',
                        'options' => array(
                            'format' => 'Y-m-d',
                        )
                    )
                )
            ));
            
            // Summary
            $inputFilter->add(array(
                'name' => 'Summary',
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
                            'min' => 1, // might change
                            'max' => 500
                        )
                    )
                )
            ));
            
            // Content
            $inputFilter->add(array(
                'name' => 'Content',
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
                            'min' => 1 // might change
                        )
                    )
                )
            ));
    
            // Verified
            $inputFilter->add(array(
                'name' => 'Verified',
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
                            'pattern' => '/^(verified)*$/i'
                        )
                    )
                )
            ));
    
            $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
}
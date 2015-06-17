<?php
namespace ReviewSec\Model\Entity\Amazon;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Product implements InputFilterAwareInterface
{

    public $ASIN;

    public $URL;

    public $AverageRating;

    public $Name;

    public $ImageURL;

    public $Price;
    
    public $Discount;

    public $MerchantName;

    public $MerchantURL;

    public $Category;

    public $Status;

    public $NumberOfTotalReviews;

    public $NumberOfAvailableReviews;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->ASIN = (! empty($data['ASIN'])) ? $data['ASIN'] : null;
        $this->URL = (! empty($data['URL'])) ? $data['URL'] : null;
        $this->AverageRating = (! empty($data['AverageRating'])) ? $data['AverageRating'] : null;
        $this->Name = (! empty($data['Name'])) ? $data['Name'] : null;
        $this->ImageURL = (! empty($data['ImageURL'])) ? $data['ImageURL'] : null;
        $this->Price = (! empty($data['Price'])) ? $data['Price'] : null;
        $this->Discount = (! empty($data['Discount'])) ? $data['Discount'] : null;
        $this->MerchantName = (! empty($data['MerchantName'])) ? $data['MerchantName'] : null;
        $this->MerchantURL = (! empty($data['MerchantURL'])) ? $data['MerchantURL'] : null;
        $this->Category = (! empty($data['Category'])) ? $data['Category'] : null;
        $this->Status = (! empty($data['Status'])) ? $data['Status'] : null;
        $this->NumberOfTotalReviews = (! empty($data['NumberOfReviews'])) ? $data['NumberOfReviews'] : null;
        $this->NumberOfAvailableReviews = (! empty($data['NumberOfAvailableReviews'])) ? $data['NumberOfAvailableReviews'] : null;
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
                            'pattern' => '/^[A-Z0-9]{1,10}$/'
                        )
                    )
                )
            ));
            
            // URL
            $inputFilter->add(array(
                'name' => 'URL',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array( // validator must be changed later
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 5
                        )
                    )
                )
            ));
            
            // AverageRating
            $inputFilter->add(array(
                'name' => 'AverageRating',
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
                        'name' => 'Float',
                        'options' => array(
                            'min' => 1,
                            'max' => 5
                        )
                    )
                )
            ));
            
            // Name
            $inputFilter->add(array(
                'name' => 'Name',
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
                            'min' => 5 // might change
                                                )
                    )
                )
            ));
            
            // ImageURL
            $inputFilter->add(array(
                'name' => 'ImageURL',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array( // validator must be changed later
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 5
                        )
                    )
                )
            ));
            
            // Price
            $inputFilter->add(array(
                'name' => 'Price',
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
                        'name' => 'Float',
                        'options' => array(
                            'min' => 0
                        )
                    )
                )
            ));
            
            // MerchantName
            $inputFilter->add(array(
                'name' => 'MerchantName',
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
                            'min' => 3 // might change
                                                )
                    )
                )
            ));
            
            // MerchantURL
            $inputFilter->add(array(
                'name' => 'MerchantURL',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array( // validator must be changed later
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 5
                        )
                    )
                )
            ));
            
            // Category
            $inputFilter->add(array(
                'name' => 'Category',
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
                            'pattern' => '/^[A-Z][\w\s&,]+$/'
                        )
                    )
                )
            ));
            
            // NumberOfTotalReviews
            $inputFilter->add(array(
                'name' => 'NumberOfReviews',
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
            
            // Discount
            $inputFilter->add(array(
                'name' => 'Discount',
                'required' => false,
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
                            'max' => 99 // any update?
                        )
                    )
                )
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}
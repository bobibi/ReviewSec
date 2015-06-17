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
namespace ReviewSec\Form;

use Zend\Form\Form;

class QueryForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct($name);
        
        $this->add(array(
            'name' => 'ProductID',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'Site',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'ProductLink',
            'type' => 'Text',
            'attributes' => array()
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array()
        ));
    }
}

















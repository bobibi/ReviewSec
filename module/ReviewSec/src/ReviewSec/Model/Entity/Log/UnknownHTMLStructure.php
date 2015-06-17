<?php
namespace ReviewSec\Model\Entity\Log;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UnknownHTMLStructure implements InputFilterAwareInterface
{
	protected $Site;
	protected $ItemName;
	protected $ItemID;
	protected $FieldName;
	protected $URL;
	protected $Notes;
}
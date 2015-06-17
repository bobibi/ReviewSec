<?php
namespace ReviewSecTest\Controller;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class RestControllerTest extends AbstractHttpControllerTestCase
{

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(include '/var/www/reviewsec/config/application.config.php');
        parent::setUp();
    }

    public function testGetListCanBeAccessed()
    {
        $result = $this->dispatch('/rest', 'POST', array('id'=>1));
        
        $this->assertResponseStatusCode(200);
    }
}
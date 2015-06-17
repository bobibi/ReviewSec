<?php

namespace ReviewSecTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class WebControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    
    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/reviewsec/config/application.config.php'
        );
        parent::setUp();
    }
    
    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/web');
        $this->assertResponseStatusCode(200);
    
        $this->assertModuleName('ReviewSec');
        $this->assertControllerName('ReviewSec\Controller\Web');
        $this->assertControllerClass('WebController');
        $this->assertMatchedRouteName('web');
    }
}
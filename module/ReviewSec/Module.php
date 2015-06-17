<?php
namespace ReviewSec;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

use ReviewSec\Model\Database as ReviewSecDatabase;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function onBootstrap($e)
    {
        // Register a render event
        $app = $e->getParam('application');
        $app->getEventManager()->attach('render', array(
            $this,
            'registerJsonStrategy'
        ), 100);
    }

    public function registerJsonStrategy($e)
    {
        $matches = $e->getRouteMatch();
        if (! $matches) {
            return;
        }
        $controller = $matches->getParam('controller');
        if (false === strpos($controller, __NAMESPACE__) || false == strpos($controller, 'Rest')) {
            // not a controller from this module
            return;
        }
        
        // Potentially, you could be even more selective at this point, and test
        // for specific controller classes, and even specific actions or request
        // methods.
        
        // Set the JSON strategy when controllers from this module are selected
        $app = $e->getTarget();
        $locator = $app->getServiceManager();
        $view = $locator->get('Zend\View\View');
        $jsonStrategy = $locator->get('ViewJsonStrategy');
        
        // Attach strategy, which is a listener aggregate, at high priority
        $view->getEventManager()->attach($jsonStrategy, 100);
    }
    
    // Service Manager:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                // Logger
                'ReviewSec\Logger' => function ($sm)
                {
                    $logger = new Logger();
                    $writer = new Stream('debuglog.txt');
                    $logger->addWriter($writer);
                    return $logger;
                },
                // Database
                'ReviewSec\Model\Database' => function ($sm)
                {
                    return new ReviewSecDatabase($sm);
                }
            )
        );
    }
}

<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Controller;

use Bolt\Extension\ExtensionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Controller\CommonControllerTrait;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bolt\Storage\Database\Connection;
use Silex\Application;
use LaterJobApi\Controllers\MonitorController;

class QueueMonitorController extends MonitorController implements ControllerProviderInterface
{
    
    
    use CommonControllerTrait;
   
    
    public function __construct(array $aConfig, Application $oContainer, ExtensionInterface $oExtension)
    {
        $this->aConfig    = $aConfig;
        $this->oContainer = $oContainer;
        $this->app        = $oContainer; // needed for ActivityProvider
        $this->oExtension = $oExtension;
        
        parent::__construct('bm.queue.queue');
    }
    
    
    
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/monitoring', array($this,'getMonitoring'))
                    ->bind('bookme-queue-monitor');

        return $controllers;
    }
    
   
    
    
    
}
/* End of File */
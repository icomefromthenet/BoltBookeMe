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
use LaterJobApi\Controllers\QueueController;

class QueueJobController extends QueueController implements ControllerProviderInterface
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

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
        
        $controllers->get('/jobs/{job}', array($this,'getJobAction'))
                    ->assert('job', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}')
                    ->convert('job',array($this,'lookupJob'))
                    ->bind('bookme-queue-job');
                    
        $controllers->get('/jobs', array($this,'getJobsAction'))
                    ->bind('bookme-queue-jobs');

        $controllers->delete('/jobs/{job}', array($this,'deleteJobAction'))
                    ->assert('job', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}')
                    ->convert('job',array($this,'lookupJob'))
                    ->bind('bookme-queue-job-delete');
                    
        $controllers->delete('/jobs', array($this,'deleteJobsAction'))
                    ->bind('bookme-queue-jobs-delete');

        return $controllers;
    }
    
   
    
    
    
}
/* End of File */
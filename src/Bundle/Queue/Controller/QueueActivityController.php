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
use LaterJobApi\Controllers\ActivityController;

class QueueActivityController extends ActivityController implements ControllerProviderInterface
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


 
        $controllers->get('list',array($this, 'onListLoadScreen'))
                    ->bind('bookme-queue-page');
        
        $controllers->get('', array($this,'getActivities'))
                    ->bind('bookme-queue-list');;
        
        $controllers->delete('', array($this,'deleteActivities'))
                    ->bind('bookme-queue-purge');;

        return $controllers;
    }
    
    /**
     * Display the Queue Activity Screen search screen
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onListLoadScreen(Application $app, Request $request)
    {
        
        $oDatabase = $this->getDatabaseAdapter();
        $oNow      = $this->getNow();
        
        $oDataTable = $this->getDataTable('queue-activity');
        $oSearchForm = $this->getForm('queue.jobsearch');
        
        //bind request vars to datatable data url
        $oDataTable->getOptionSet('AjaxOptions')->setRequestParams($request->query->all());
        
        //incude request params as values to our form
        //$oSearchForm->setValuesFromRequest($request);
        
        
        $aDataTable = [
            'sConfigString'     => $oDataTable->writeConfig(), 
            'aEvents'           => $oDataTable->getEvents(),
            //'sFormConfigString' => $oSearchForm->getJSON(),
            'oForm'             =>  $oSearchForm->getForm()->createView(),
        ];  
       
       return $app['twig']->render('@Queue/view_queue.twig', array_merge(['title' => 'Schedule Rebuild Queue'], $aDataTable), []);

    }
    
    
    
}
/* End of File */
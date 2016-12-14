<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover\Controller;

use DateTime;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bolt\Storage\Database\Connection;
use Bolt\Extension\IComeFromTheNet\BookMe\Controller\CommonController;



/**
 * Preview and Process Schedule Rollover.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class RolloverController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->get('',[$this,'onRolloverPreviewGet'])
              ->bind('bookme-rollover-preview');
   
   
      
    
        
        return $oCtr;
    }

    /**
     * Display a preview of the schedule that be rolled over
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onRolloverPreviewGet(Application $app, Request $request)
    {
       
       $oDatabase = $this->getDatabaseAdapter();
       $oNow      = $this->getNow();
      
       
        $oDataTable  = $this->getDataTable('queue-activity');
        $oSearchForm = $this->getForm('queue.jobsearch');
        
        //bind request vars to datatable data url
        $oDataTable->getOptionSet('AjaxOptions')->setRequestParams($request->query->all());
        
        //incude request params as values to our form
        //$oSearchForm->setValuesFromRequest($request);
        
        
        $aOption = [
            'sConfigString'     => $oDataTable->writeConfig(), 
            'aEvents'           => $oDataTable->getEvents(),
            'oForm'             =>  $oSearchForm->getForm()->createView(),
        ];  
       
       return $app['twig']->render('@Rollover/view_preview.twig', array_merge(['title' => 'Preview Schedules'], $aOption), []);
       
    }

   
}
/* End of Class */

<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Controller;

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
 * Preview and Process Holiday Rule generator
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class HolidayRuleController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->get('',[$this,'onRolloverPreviewGet'])
              ->bind('bookme-holidayrule-preview');
   
   
      
    
        
        return $oCtr;
    }

    /**
     * Display a preview of the holiday rules that can be generated
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onHolidayRulePreviewGet(Application $app, Request $request)
    {
       
       $oDatabase = $this->getDatabaseAdapter();
       $oNow      = $this->getNow();
      
       $oSearchForm = $this->getForm('queue.jobsearch');
        
       //bind request vars to datatable data url
       $oDataTable->getOptionSet('AjaxOptions')->setRequestParams($request->query->all());
        
        //incude request params as values to our form
        //$oSearchForm->setValuesFromRequest($request);
        
        
        $aOption = [
            'title'     => 'Choose Holiday',
            'oForm'     =>  $oSearchForm->getForm()->createView(),
        ];  
       
       return $app['twig']->render('@HolidayRule/preview_holiday.twig', $aOption, []);
       
    }

   
}
/* End of Class */

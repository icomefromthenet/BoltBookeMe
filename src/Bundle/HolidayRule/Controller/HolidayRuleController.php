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

        $oCtr->get('',[$this,'onHolidayRulePreview'])
              ->bind('bookme-holidayrule-preview');
   
        $oCtr->post('',[$this,'onHolidayRuleSave'])
              ->bind('bookme-holidayrule-save');
      
    
        
        return $oCtr;
    }
    
    /**
     * Saves a Selected Holiday Rules for the Given Schedule Year
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onHolidayRuleSave(Application $app, Request $request)
    {
        
    
    }
    

    /**
     * Display a preview of the holiday rules that can be generated
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onHolidayRulePreview(Application $app, Request $request)
    {
        $oSearchForm  = $this->getForm('holidayrule.builder')->getForm();
        $aErrors = [];
        $aHolidays = [];
        
        $oSearchForm->handleRequest($request);
    
        if ($oSearchForm->isSubmitted() && $oSearchForm->isValid()) {
            $aSearch = $oSearchForm->getData();
         
            $sHolidayProvider = $this->oContainer['bm.holidayrule.choicelist'][$aSearch['sHolidayProvider']];
         
            $aHolidays = \Yasumi\Yasumi::create($sHolidayProvider, $aSearch['iCalYear']->getCalendarYear());
            
        } 
        
        $aOption = [
            'title'     => 'Choose Holiday',
            'oForm'     => $this->getForm('holidayrule.view'),  
            'aHoliday'  => $aHolidays
        ];  
       
       
       return $app['twig']->render('@HolidayRule/preview_holiday.twig', $aOption, []);
       
    }

   
}
/* End of Class */

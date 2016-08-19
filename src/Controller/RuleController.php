<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Controller;

use DateTime;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bolt\Storage\Database\Connection;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;


/**
 * View and manage schedule rules
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class RuleController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->get('',[$this,'onRuleList'])
              ->bind('bookme-rule-list');
   
    
        return $oCtr;
    }

    /**
     * Load a list of schedule rules
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onRuleList(Application $app, Request $request)
    {
       
       $oDatabase = $this->getDatabaseAdapter();
       $oNow      = $this->getNow();
       
      
       //return $app['twig']->render('admin_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear, 'timeslots' => $aTimeslots], []);
    }


    public function onRulePost(Request $request)
    {
       $oDatabase   = $this->getDatabaseAdapter();
       $oNow        = $this->getNow();
       $oCommandBus = $this->getCommandBus();
       
       $oStartFromDate     = $request->get;
       $oEndtAtDate
       $iRuleTypeDatabaseId
       $iTimeslotDatabaseId
       $iOpeningSlot
       $iClosingSlot
       $sRepeatDayofweek
       $sRepeatDayofmonth
       $sRepeatMonth
       $bIsSingleDay
       
       
       $oCommand    = new CreateRuleCommand($oStartFromDate, $oEndtAtDate, $iRuleTypeDatabaseId
                              , $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot
                              , $sRepeatDayofweek, $sRepeatDayofmonth, $sRepeatMonth, $bIsSingleDay);
        
    }
   
}
/* End of Calendar Admin Controller */

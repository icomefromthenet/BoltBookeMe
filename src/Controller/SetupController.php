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
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\CalAddYearCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\RolloverTimeslotCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\SlotAddCommand;


/**
 * Calendar and timeslot Admin Controller
 * 
 * Used to view and manage calendar years.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class SetupController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->get('calendar',[$this,'onSetupCalendar'])
             ->post('calendar',[$this,'onAddCalendarPost'])
             ->bind('bookme-setup-calendar');
   
        $oCtr->get('timeslot',[$this,'onSetupTimeslot'])
              ->post('timeslot',[$this,'onAddTimeslotPost'])
              ->bind('bookme-setup-timeslot');
   
     
        
        return $oCtr;
    }

    /**
     * Load the backend Calendar Config Page.
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onSetupCalendar(Application $app, Request $request)
    {
       
       $oDatabase = $this->getDatabaseAdapter();
       $oNow      = $this->getNow();
       
       # load a list of active calendars for display in the template
       $aCalendarYearList = $oDatabase->fetchArray('SELECT y 
                                                    FROM bolt_bm_calendar_years 
                                                    ORDER BY y DESC');
       # if we don't have any calendars setup which true on first install
       # add the current year 
       $iCalCount = count($aCalendarYearList);
       
       if(0 === $iCalCount) {
           $iNextCalendarYear = $oNow->format('Y');  
       } else {
           $iNextCalendarYear = $aCalendarYearList[$iCalCount] + 1;
       }
       
       # load a list of timeslots
       $aTimeslots = $oDatabase->fetchArray('SELECT y 
                                                    FROM bolt_bm_calendar_years 
                                                    ORDER BY y DESC');
       
       return $app['twig']->render('admin_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear, 'timeslots' => $aTimeslots], []);
    }

     /**
     * Load the backend Calendar Config Page.
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onSetupTimeslot(Application $app, Request $request)
    {
       
       $oDatabase = $this->getDatabaseAdapter();
       $oNow      = $this->getNow();
       
       # load a list of active calendars for display in the template
       $aCalendarYearList = $oDatabase->fetchArray('SELECT y 
                                                    FROM bolt_bm_calendar_years 
                                                    ORDER BY y DESC');
       # if we don't have any calendars setup which true on first install
       # add the current year 
       $iCalCount = count($aCalendarYearList);
       
       if(0 === $iCalCount) {
           $iNextCalendarYear = $oNow->format('Y');  
       } else {
           $iNextCalendarYear = $aCalendarYearList[$iCalCount] + 1;
       }
       
       # load a list of timeslots
       $aTimeslots = $oDatabase->fetchArray('SELECT y 
                                                    FROM bolt_bm_calendar_years 
                                                    ORDER BY y DESC');
       
       return $app['twig']->render('admin_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear, 'timeslots' => $aTimeslots], []);
    }

    /**
     * Handles request to create new calendar year(s)
   
     * @param Request $request
     *
     * @return Response
     */
    public function onAddCalendarPost(Application $app, Request $request)
    {
        $oCommandBus    = $this->getCommandBus();
        $oDatabase      = $this->getDatabaseAdapter();
        $aConfig        = $this->getExtensionConfig();
        $sTimeSlotTable = $aConfig['tablenames']['bm_timeslot'];
        
        # Add Calendar for the given years
        $iCalenderYear = $request->request->get('iCalendarYear');
        
        $oStartYear = DateTime::createFromFormat('Y-m-d',$iCalenderYear.'-01-01');
        
        if(true === empty($oStartYear)) {
            throw new \RuntimeException('Unable to create a starting date');
        }
        
        # Create the new calendar Year
        
        $oCommand = new CalAddYearCommand(1,$oStartYear);
        
        $oCommandBus->handle($oCommand);
        
        # build time slots for this new year
        
        $aSlots = $oDatabase->fetchAll("SELECT timeslot_id FROM $sTimeSlotTable");
        
        foreach( $aSlots as $aSlot) {
        
            $oCommand = new RolloverTimeslotCommand($aSlot['timeslot_id']);
            $oCommandBus->handle($oCommand);
        }
        
        # redirect back to admin page when sucessful
        $this->getFlash()->success('Created new Calendar Year '.$iCalenderYear);

        return $app->redirect('/bolt/extend/bookme/home/calendar');
    }


    /**
     * Handles request to create new timeslot.
     *
     * @param Application $app
     * @param Request $request
     *
     * @return Response
     */
    public function onAddTimeslotPost(Application $app, Request $request)
    {
        $oCommandBus    = $this->getCommandBus();
        $oDatabase      = $this->getDatabaseAdapter();
        $oNow           = $this->getNow();
        
        $iSlotLength = $request->request->get('iSlotLength');
    
        
        $oCommand = new SlotAddCommand($iSlotLength,$oNow->format('Y'));
    
        $oCommandBus->handle($oCommand);
        
        if(true === empty($oCommand->getTimeSlotId())) {
            $this->getFlash()->warning('Unable to create the new slot');
        }
        
        $this->getFlash()->success('Created new time slot '.$iSlotLength);

    
        return $app->redirect('/bolt/extend/bookme/home/timeslot');
    }
}
/* End of Calendar Admin Controller */

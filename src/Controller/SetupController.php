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
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\SlotToggleStatusCommand;


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
             ->bind('bookme-setup-calendar');
        
        $oCtr->post('calendar',[$this,'onAddCalendarPost']);
    
   
        $oCtr->get('timeslot',[$this,'onSetupTimeslot'])
              ->bind('bookme-setup-timeslot');
  
        $oCtr->post('timeslot',[$this,'onAddTimeslotPost']);
   
        $oCtr->post('timeslot/toggle',[$this,'onTimeslotToggle'])
             ->bind('bookme-setup-timeslot-toggle');
             
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
       
       $oDatabase     = $this->getDatabaseAdapter();
       $oNow          = $this->getNow();
       $aConfig       = $this->getExtensionConfig();
       $sCalYearTable = $aConfig['tablenames']['bm_calendar_years'];
       
       # load a list of active calendars for display in the template
       $aCalendarYearList = $oDatabase->fetchAll("SELECT y 
                                                    FROM $sCalYearTable 
                                                    ORDER BY y ASC");
       
       
       # if we don't have any calendars setup which true on first install
       # add the current year 
       $iCalCount = count($aCalendarYearList);
       
      if(true == empty($aCalendarYearList)) {
           $iNextCalendarYear = $oNow->format('Y');  
       } else {
           $aLastYear = end($aCalendarYearList);
           $iNextCalendarYear = $aLastYear['y'] + 1;
       }
    
       
       return $app['twig']->render('@BookMe/setup_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear], []);
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
        $iCalenderYear = trim($request->request->get('iCalendarYear'));
        
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

        return $app->redirect('/bolt/extend/bookme/setup/calendar/');
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
       
       $oDatabase       = $this->getDatabaseAdapter();
       $oNow            = $this->getNow();
       $aConfig         = $this->getExtensionConfig();
       $sTimeSlotTable  = $aConfig['tablenames']['bm_timeslot'];
       
       # load a list of active calendars for display in the template
       $aTimeslots = $oDatabase->fetchAll("SELECT timeslot_id, timeslot_length, is_active_slot
                                           FROM $sTimeSlotTable
                                           ORDER BY timeslot_length DESC");
     
       
       return $app['twig']->render('@BookMe/setup_timeslot.twig', ['title' => 'Setup Timeslot','timeslots' => $aTimeslots], []);
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
        
        $this->getFlash()->success('Created new time slot with length of '.$iSlotLength. ' minutes');

    
        return $app->redirect('/bolt/extend/bookme/setup/timeslot');
    }
    
    /**
     * Handles request to change timeslot status to hidden
     *
     * @param Application $app
     * @param Request $request
     *
     * @return Response
     */
    public function onTimeslotToggle(Application $app, Request $request)
    {
        $oCommandBus    = $this->getCommandBus();
        $oDatabase      = $this->getDatabaseAdapter();
        $oNow           = $this->getNow();
        
        $iSlotId = $request->request->get('iTimeslotId');
    
        
        $oCommand = new SlotToggleStatusCommand($iSlotId);
    
        $oCommandBus->handle($oCommand);
        
        if(true === empty($oCommand->getTimeSlotId())) {
            $this->getFlash()->warning('Unable set slot status to hidden at ID '.$iSlotId);
        }
        
        $this->getFlash()->success('Slot is now hidden at ID '.$iSlotId);

    
        return $app->redirect('/bolt/extend/bookme/setup/timeslot');
    }
}
/* End of Calendar Admin Controller */

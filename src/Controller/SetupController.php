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

/**
 * Calendar and timeslot Admin Controller
 * 
 * Used to view and manage calendar years.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class SetupController implements ControllerProviderInterface
{
    /** 
     * @var array The extension's configuration parameters
     */ 
    protected $config;

    /**
     * @var Bolt\Storage\Database\Connection    The Database Connection
     */ 
    protected $oDatabase;
    
    /**
     * @var DateTime    The Processing Date
     */ 
    protected $oNow;

    
    
    
    public function __construct(array $config, DateTime $oNow, Connection $oDatabase)
    {
        $this->config    = $config;
        $this->oDatabase = $oDatabase;
        $this->oNow      = $oNow;
    }


    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->get('',[$this,'onSetupGet'])
              ->bind('bookme-setup');
   
   
        $oCtr->post('calendar',[$this,'onAddCalendarPost'])
              ->bind('bookme-setup-calendar-add');
    
        $oCtr->post('timeslot',[$this,'onAddTimeslotPost'])
              ->bind('bookme-setup-timslot-add');
    
        
        return $oCtr;
    }

    /**
     * Load the backend Calendar Config Page.
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onSetupGet(Application $app, Request $request)
    {
       
       $oDatabase = $this->oDatabase;
       $oNow      = $this->oNow;
       
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
   
     * @param Application $app
     * @param Request $request
     *
     * @return Response
     */
    public function onAddCalendarPost(Application $app, Request $request)
    {
        $oCommandBus = $app['bm.commandBus'];
        
        
        
        # Add Calendar for the given years
        
        # redirect back to admin page when sucessful

        return $jsonResponse;
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
        $oCommandBus = $app['bm.commandBus'];
        
        
        
        # Add Calendar for the given years
        
        # redirect back to admin page when sucessful

        return $jsonResponse;
    }
}
/* End of Calendar Admin Controller */

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
 * Calendar Admin Controller
 * 
 * Used to view and manage calendar years.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class CalendarAdminController implements ControllerProviderInterface
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

        $oCtr->get('admin',[$this,'onCalendarAdminGet'])
              ->bind('bookme-admin-calendar');
   
   
        $oCtr->post('admin',[$this,'onCalendarAdminPost'])
              ->bind('bookme-admin-calendar-add');
    
        
        return $oCtr;
    }

    /**
     * Load the backend Calendar Config Page.
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onCalendarAdminGet(Application $app, Request $request)
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
       
       
       return $app['twig']->render('admin_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear ], []);
    }

    /**
     * Handles POST requests on admin and return with a redirect
     *
     * This will add the required calendar year. 
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function onCalendarAdminPost(Application $app, Request $request)
    {
        $oCommandBus = $app['commandBus'];
        
        
        
        # Add Calendar for the given years
        
        # redirect back to admin page when sucessful

        return $jsonResponse;
    }

}
/* End of Calendar Admin Controller */

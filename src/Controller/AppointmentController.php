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
 * List and Search Appointments
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class AppointmentController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->get('',[$this,'onApptSearchGet'])
              ->bind('bookme-appt-search');
   
   
      
    
        
        return $oCtr;
    }

    /**
     * Display the Appointment search screen
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onApptSearchGet(Application $app, Request $request)
    {
       
       $oDatabase = $this->getDatabaseAdapter();
       $oNow      = $this->getNow();
      
       
       //return $app['twig']->render('admin_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear, 'timeslots' => $aTimeslots], []);
    }

   
}
/* End of Calendar Admin Controller */

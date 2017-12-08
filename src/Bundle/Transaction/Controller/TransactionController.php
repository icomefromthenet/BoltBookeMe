<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Controller;

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
 * Unknown
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class TransactionController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

       
         $oCtr->get('report/payments',[$this,'onViewSalesReport'])
              ->bind('bookme-transaction-report-sales');
   
        
          $oCtr->get('report/sales',[$this,'onViewPaymentsReport'])
              ->bind('bookme-transaction-report-payments');
   
       
        
        return $oCtr;
    }
    
    
    /**
     * Display the Sales Report screen
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onViewSalesReport(Application $app, Request $request)
    {
       
       $oDatabase = $this->getDatabaseAdapter();
       $oNow      = $this->getNow();
      
       
       //return $app['twig']->render('admin_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear, 'timeslots' => $aTimeslots], []);
    }
   
    
    /**
     * Display the Payments Report screen
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onViewPaymentsReport(Application $app, Request $request)
    {
       
       $oDatabase = $this->getDatabaseAdapter();
       $oNow      = $this->getNow();
      
       
       //return $app['twig']->render('admin_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear, 'timeslots' => $aTimeslots], []);
    }
  
   
   
}
/* End of Class */

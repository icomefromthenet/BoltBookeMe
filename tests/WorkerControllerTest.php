<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpFoundation\Request;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Controller\WorkerController;


class WorkerControllerTest extends ExtensionTest
{
    
    
    protected $aDatabaseId = [];
    
    
    
   protected function handleEventPostFixtureRun()
   {
      // Create the Calendar 
      $oService = $this->getTestAPI();
      
      $oService->addCalenderYears(1);
      
      $oNow     = $this->getNow();
      
      $iFiveMinuteTimeslot    = $oService->addTimeslot(5,$oNow->format('Y'));
      $iTenMinuteTimeslot     = $oService->addTimeslot(10,$oNow->format('Y'));
      $iFifteenMinuteTimeslot = $oService->addTimeslot(15,$oNow->format('Y'));

      $oService->toggleSlotAvability($iTenMinuteTimeslot);    
  
            
            
      $this->aDatabaseId = [
        'five_minute'    => $iFiveMinuteTimeslot,
        'ten_minute'     => $iTenMinuteTimeslot,
        'fifteen_minute' => $iFifteenMinuteTimeslot,
      ];
      
      
   }  
   
   
    /**
    * @group Management
    */ 
    public function testWorkerController()
    {
       $iCalYear = $this->getNow()->format('Y');
      
       $this->AddTimeslotTest(30);
       $this->AddCalendarYearTest($iCalYear+1);  
       
       
    }
    
    protected function AddTimeslotTest($iSlotLength)
    {
        $oApp        = $this->getApp();
        $oContainer  = $this->getContainer();
        $aConfig     = $this->getAppConfig();
       
        $oController = new SetupController($aConfig,$oContainer);
        
        $oRequest    = new Request(array(),array('iSlotLength'=>$iSlotLength));
     
        $oController->onAddTimeslotPost($oApp,$oRequest);
     
        $iYearCount = (int) $this->getDatabaseAdapter()->fetchColumn("
                            select COUNT( DISTINCT y.y )  
                            from bolt_bm_timeslot_year y 
                            join bolt_bm_timeslot b on b.timeslot_id = y.timeslot_id 
                            where b.timeslot_length = :iSlotLength"
                            ,[':iSlotLength' => $iSlotLength]);
       
       $this->assertEquals(1,$iYearCount);    
        
    }
    
    protected function AddCalendarYearTest($iCalYear)
    {
        $oApp        = $this->getApp();
        $oContainer  = $this->getContainer();
        $aConfig     = $this->getAppConfig();
       
        $oController = new SetupController($aConfig,$oContainer);
        
        $oRequest = new Request(array(),array('iCalendarYear'=>$iCalYear));
        
        $oController->onAddCalendarPost($oApp,$oRequest);
        
        
        # assert we have new calendar year
        $aDates = $this->getDatabaseAdapter()->fetchArray("select date_format(max(calendar_date),'%Y-%m-%d') as max from bolt_bm_calendar");
        $oMaxDateTime = \DateTime::createFromFormat('Y-m-d',$aDates[0]);
       
        $this->assertEquals($iCalYear.'-12-31', $oMaxDateTime->format('Y-m-d'));
        
        
        # assert timeslots setup in new cal year
        # have one extra timeslot from above test which run first
        $iSlotCount = (int) $this->getDatabaseAdapter()->fetchColumn("select COUNT( DISTINCT timeslot_id )  from bolt_bm_timeslot_year
                                                                      where y = :iNewCalYear" ,[':iNewCalYear' => $iCalYear],0,[]);
        $this->assertEquals(4,$iSlotCount);
    }
    
    
   
    
    
}
/* end of file */

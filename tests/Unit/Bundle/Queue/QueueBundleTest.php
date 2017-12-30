<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Queue;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Model\RefreshScheduleDecorator;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\RefreshScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;


class QueueBundleTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
        $oNow         = $this->getNow();
        $oService     = $this->getTestAPI();
        
        return;
   }  
   
   
   public function testQueueBundle()
   {
      $this->QueueBuildsSucessfulyTest();
      $this->QueueRefreshHandlerTest($this->aDatabaseId['schedule_member_one']);
   }
   
    
   public function QueueBuildsSucessfulyTest()
   {
       $oQueue = $this->getContainer()->offsetGet('bm.queue.queue');
       
       $this->assertInstanceOf('LaterJob\Queue',$oQueue);
   }
   
   
   public function QueueRefreshHandlerTest($iScheduleID)
   {
      $oCommand = new RefreshScheduleCommand($iScheduleID,true);
      $oDatabase = $this->getDatabaseAdapter();
      $oRefreshScheduleHandler = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\RefreshScheduleHandler')
                                      ->disableOriginalConstructor()
                                      ->getMock();
      $aConfig = $this->getAppConfig();
      
      $sQueueActivityTable = $aConfig['tablenames']['bm_queue'];
                                                  
      $oRefreshScheduleHandler = new RefreshScheduleDecorator($oRefreshScheduleHandler
      , $aConfig['tablenames']
      , $oDatabase
      , $this->getContainer()->offsetGet('bm.queue.queue')
      , $this->getContainer()->offsetGet('bm.now'));
      
      
      // Can we inert into queue
      $oRefreshScheduleHandler->handle($oCommand);
      
      //verify job is in queue
      
      $aResult = $oDatabase->fetchAssoc("SELECT job_id, job_data from $sQueueActivityTable"); 
      
      $this->assertNotEmpty($aResult);
      
      $aJobData = unserialize($aResult['job_data']);
      
      $this->assertEquals(1,$aJobData);
      
      
   }
   
   
  
   
   
}
/* end of file */

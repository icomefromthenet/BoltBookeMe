<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Queue;

use DateTime;
use Silex\Application;
use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\RefreshScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Model\RefreshScheduleDecorator;

class QueueWorkerTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
        $oNow         = $this->getNow();
        $oService     = $this->getTestAPI();
        
        return;
   }  
   
   
   public function testQueueWorkers()
   {
        $oApp = $this->getContainer();
        $oBus = $this->getCommandBus();
        $oWorker = $oApp['bm.queue.worker.rebuild'];
        $oMonitorWorker = $oApp['bm.queue.worker.monitor'];
        $oPurgeWorker = $oApp['bm.queue.worker.purge'];
        
        // Add some jobs onto the queue
        
        
        foreach($this->aDatabaseId as $key => $value) {
            
            if (true === in_array($key,['schedule_member_one','schedule_member_two','schedule_member_three','schedule_member_four'])) {
                $oCommand   = new RefreshScheduleCommand($value,true);
                
                $oBus->handle($oCommand);
            }
            
        }
        
        $phpunit = $this;
        
        // override the command bus handler and ensure that handle been called
        $oApp['bm.model.schedule.handler.refresh'] = $oApp->share($oApp->extend('bm.model.schedule.handler.refresh',
            function($oRefreshHandler, Application $container) use ($phpunit){
                $oMock =  $phpunit->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Model\RefreshScheduleDecorator')
                                ->disableOriginalConstructor()
                                ->getMock(); 
                                
                $oMock->expects($phpunit->exactly(4))
                      ->method('handle');

                return $oMock;
        }));
        
        
        // Execute the worker and assert items have completed.
        $oWorker();
        
        
        //Execute Monitor Worker
        $oMonitorWorker();
        
        
        //Execute Purge Worker
        $oPurgeWorker();
        
   }
   
   
}
/* end of file */

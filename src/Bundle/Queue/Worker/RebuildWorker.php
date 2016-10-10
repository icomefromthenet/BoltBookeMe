<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker;

use DateTime;
use LaterJob\Queue;
use LaterJob\Exception as LaterJobException;
use League\Tactician\CommandBus;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;


/**
 * Worker for the Rebuild Schedule Jobs.
 *
 * @author Your Name <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class RebuildWorker
{
    /**
     * @var LaterJob\Queue;
     */ 
    protected $oQueue;
    
    /**
     * @var DateTime
     */ 
    protected $oNow;
    
    /**
     * @var League\Tactician\CommandBus
     */ 
    protected $oCommandBus;
    
    
    public function __construct(Queue $oQueue, DateTime $oNow, CommandBus $oCommandBus)
    {
        $this->oQueue      = $oQueue;
        $this->oNow        = $oNow;
        $this->oCommandBus = $oCommandBus;
        
        
    }
    
    
    
    public function __invoke()
    {
        $oQueue = $this->oQueue;
        $oNow   = $this->oNow;
        $oBus   = $this->oCommandBus;
        $worker = $oQueue->worker();
        
        
        try {
            // start the worker with the assigned date
            $worker->start($oNow);
            
            $allocator = $worker->receive($oNow);
            
            $handle = $worker->getId();
            
            foreach($allocator as $job) {
            
                try {
                    
                    // since time can pass between job finishing we need
                    // use a new datetime so we know how much has passed since
                    // started processing
                    $job->start($handle, new DateTime());
                 
                    $iScheduleId = $job->getData();
                 
                    // Create new Refresh Command
                    $oCommand = new RefreshScheduleCommand($iScheduleId,false);
                    
                    // Pass command to bus for procesing, will throw schedueException
                    // if unable to process this schedule or validate failure
                    $oBus->handle($oCommand);
                   
                    
                    // Mark this job as finished, using new date time
                    // so we know how much time has passed
                    $job->finish($handle,new DateTime());
                }
                catch(LaterJobException $e) {
                    
                    if($job->getRetryCount() > 0) {
                        $job->error($handle,new DateTime(),$e->getMessage());    
                    }
                    else {
                        $job->fail($handle,new DateTime(),$e->getMessage());    
                    }
                }
                catch (ScheduleException $e) {
                    if($job->getRetryCount() > 0) {
                        $job->error($handle,new DateTime(),$e->getMessage());    
                    }
                    else {
                        $job->fail($handle,new DateTime(),$e->getMessage());    
                    }
                }
                catch(ValidationException $e) {
                     // validation failure this job will never process os
                     // lets fail it now.
                     $job->fail($handle,new DateTime(),$e->getMessage().' For Schedule at::'.$iScheduleId);  
                }
                
            }
            
            // finish the worker with a new date so we know how much
            // time has passed since start time
            $worker->finish(new DateTime());
            
        } catch(LaterJobException $e) {
            $worker->error($handle,new DateTime(),$e->getMessage());
            throw $e;            
        }

    }
    
    
}
/* End of Class */
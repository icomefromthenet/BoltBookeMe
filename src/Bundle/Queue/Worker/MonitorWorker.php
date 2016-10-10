<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker;

use DateTime;
use LaterJob\Queue;
use LaterJob\Exception as LaterJobException;



/**
 * Worker for the monitor Schedule Jobs.
 *
 * @author Your Name <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class MonitorWorker
{
    /**
     * @var LaterJob\Queue;
     */ 
    protected $oQueue;
    
    /**
     * @var DateTime
     */ 
    protected $oNow;
    
 
    
    public function __construct(Queue $oQueue, DateTime $oNow)
    {
        $this->oQueue      = $oQueue;
        $this->oNow        = $oNow;
        
    }
    
    
    
    public function __invoke()
    {
        $oQueue = $this->oQueue;
        $oNow   = $this->oNow;
       
        $monitor = $oQueue->monitor();
        
        # execute API Method         
        $monitor->monitor($oNow);
    
    }
    
    
}
/* End of Class */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker;

use DateTime;
use LaterJob\Queue;
use LaterJob\Exception as LaterJobException;



/**
 * Worker for the that call the queue Purge.
 *
 * @author Your Name <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class PurgeWorker
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
     * @var integer
     */ 
    protected $iDaysOld;
    
    /**
     * Fetch the max date in which to purge activity from the queue
     * 
     * @return DateTime
     */ 
    protected function getPruneDate()
    {
        $oPruneDate   = clone $this->oNow;
        $oPruneDate->modify('-'.$this->iDaysOld.' day');
        return $oPruneDate;
    }
    
    
    public function __construct(Queue $oQueue, DateTime $oNow, $iDaysOld = 30)
    {
        $this->oQueue      = $oQueue;
        $this->oNow        = $oNow;
        $this->iDaysOld    = $iDaysOld;
    }
    
    
    
    public function __invoke()
    {
        $oQueue     = $this->oQueue;
        $oPruneDate = $this->getPruneDate();
       
        $oQueue->activity()->purge($oPruneDate);
        
    }
    
    
}
/* End of Class */
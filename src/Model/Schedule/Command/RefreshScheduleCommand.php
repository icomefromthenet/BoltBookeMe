<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command;

use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to refresh a schedule by compling the rules
 * that are linked to it.
 * 
 * This will not clear bookings but my leave them in conflict.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RefreshScheduleCommand implements  ValidationInterface
{

 
    /**
    * @var integer the schedule database id
    */ 
    protected $iScheduleDatabaseId;
    
    /**
     * @var boolean if this refresh should use the jobqueue
     */ 
    protected $bSendToQueue;
    
    
    
    public function __construct($iScheduleDatabaseId, $bSendToQueue = true)
    {
        $this->iScheduleDatabaseId = $iScheduleDatabaseId;
        $this->bSendToQueue        = $bSendToQueue;
    }
    
    
  
    /**
     * Fetches the database id of the schedule to use
     * 
     * @access public
     * @return integer  The schedule Database id
     */ 
    public function getScheduleId()
    {
        return $this->iScheduleDatabaseId;
    }
    
    /**
     * If this refresh should sent to the Schedule Rebuild Queue
     * 
     * @return boolean  true if sent to queue
     * @access public
     */ 
    public function sendToQueue()
    {
        return $this->bSendToQueue;
    }
    
    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['schedule_id']
            ]
            ,'min' => [
                ['schedule_id',1]
            ]
            ,'required' => [
                ['schedule_id']
            ]
            
        ];
    }
    
    
    public function getData()
    {
        return [
            'schedule_id' => $this->iScheduleDatabaseId,
        ];
    }
    
    
   

}
/* End of Clas */
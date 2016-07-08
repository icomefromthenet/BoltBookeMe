<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to re-start a closed schedule 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ResumeScheduleCommand implements ValidationInterface, HasEventInterface
{

    
    /**
     * @var integer the database id of the new schedule once created
     */ 
    protected $iScheduleDatabaseId;
    
    /**
     * @var date to restart schedule from
     */ 
    protected $oFromDate;
    
    public function __construct($iScheduleDatabaseId, DateTime $oFromDate)
    {
        $this->iScheduleDatabaseId      = $iScheduleDatabaseId;
        $this->oFromDate                = $oFromDate;
    }
    
    
    /**
     * Fetch the date to restart schedule from
     *  
     * @return DateTime
     */ 
    public function getFromDate()
    {
        return $this->oFromDate;
    }
   
    
    /**
    * Return the schedule database id
    * 
    * @return integer 
    */ 
    public function getScheduleId()
    {
        return $this->iScheduleDatabaseId;
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
                ['schedule_id'],['from_date']
            ]
        ];
    }
    
    
    public function getData()
    {
      
      return [
        'schedule_id' => $this->iScheduleDatabaseId,
        'from_date'   => $this->oFromDate
      ];
      
    }
    
    //----------------------------------------------------------------
    # Has Event Interface
    
    public function getEvent()
    {
        return new CommandEvent($this);
    }
    
    
    public function getEventName()
    {
        return BookMeEvents::SCHEDULE_RESUME;  
    }

  
}
/* End of Clas */
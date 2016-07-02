<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command;

use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to rollover last years timeslots
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RolloverTimeslotCommand implements HasEventInterface
{

    
    
    /**
     * @var integer number of schedules that been rolledover
     */ 
    protected $iRolloverNumber;
    
    
    
    protected $iTimeslotDatabaseId;
    
    
    
    public function __construct($iTimeslotDatabaseId)
    {
        $this->iTimeslotDatabaseId = $iTimeslotDatabaseId;
    }
    
    
    /**
    * Fetch the database id of the new timeslot
    * 
    * @access public
    */ 
    public function getTimeSlotId()
    {
      return $this->iTimeslotDatabaseId;
    }
   
    
    /**
    * Return Number of timeslots rolledover
    * 
    * @return integer 
    */ 
    public function getRollOverNumber()
    {
        return $this->iRolloverNumber;
    }
    
    /**
    * Fetch Number of timeslots rolledover
    * 
    * @param integer    $iRolloverNumber    The number of schedules rolledover during this command
    */ 
    public function setRollOverNumber($iRolloverNumber)
    {
        return $this->iRolloverNumber = $iRolloverNumber;
    }
    
    
    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['timeslot_id']
            ]
            ,'min' => [
                ['timeslot_id',1]
            ]
            ,'required' => [
                ['timeslot_id']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
            'timeslot_id' => $this->iTimeslotDatabaseId,
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
        return BookMeEvents::SLOT_ROLLOVER;  
    }

  
}
/* End of Clas */
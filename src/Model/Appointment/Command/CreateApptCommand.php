<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\ApptEntity;

/**
 * This command is used to assign appointment to booking 
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateApptCommand extends ApptEntity implements  HasEventInterface, ValidationInterface
{
 
 
    public function __construct($iCustomerId, $sInstruction)
    {
        $this->iCustomerId    = $iCustomerId;
        $this->sInstructions  = $sInstruction;
        $this->sStatusCode    = 'W';
    }
 
 
    public function getAppointmentId()
    {
        return $this->iAppointmentId;
    }
 
    public function getCustomerId()
    {
        return $this->iCustomerId;
    }
    
    public function getInstructions()
    {
        return $this->sInstructions;
    }
    
    public function getStatusCode()
    {
        return $this->sStatusCode;
    }
    
    public function getAppointmentNumber()
    {
        return $this->sApptNumber;
    }
    
    //---------------------------------------------------------
    # validation interface
   
    public function getRules()
    {
        $oBaseRules = parent::getRules();
        
        // no changes required
        
        return $oBaseRules;
    }
    
    
    //----------------------------------------------------------------
    # Has Event Interface
    
    public function getEvent()
    {
      return new CommandEvent($this);
    }
    
    
    public function getEventName()
    {
        return BookMeEvents::APPT_CREATED;
    }
    

}
/* End of Clas */
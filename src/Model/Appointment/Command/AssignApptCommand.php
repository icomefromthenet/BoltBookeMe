<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\ApptEntity;

/**
 * This command is used to assign a booking to an appointment
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class AssignApptCommand extends ApptEntity implements  HasEventInterface, ValidationInterface
{
 
 
    public function __construct($iAppointmentId, $iBookingId, $sInstruction)
    {
        $this->iAppointmentId = $iAppointmentId;
        $this->iBookingId     = $iBookingId;
        $this->sInstructions  = $sInstruction;
    }
 
 
    public function getAppointmentId()
    {
        return $this->iAppointmentId;
    }
 
    public function getBookingId()
    {
        return $this->iBookingId;
    }
    
    public function getStatusCode()
    {
        return $this->sStatusCode;
    }
   
    
    //---------------------------------------------------------
    # validation interface
   
    public function getRules()
    {
        $oBaseRules = parent::getRules();
        
        $oBaseRules['required'] =  + [['iAppointmentId'],['iBookingId'],['sInstructions']];
        
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
        return BookMeEvents::APPT_ASSIGNED;
    }
    

}
/* End of Clas */
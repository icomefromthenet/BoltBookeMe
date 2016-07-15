<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\ApptEntity;

/**
 * This command is used to cancel an appointment.
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CancelApptCommand extends ApptEntity implements  HasEventInterface, ValidationInterface
{
 
 
    public function __construct($iAppointmentId)
    {
        $this->iAppointmentId = $iAppointmentId;
    }
 
 
    public function getAppointmentId()
    {
        return $this->iAppointmentId;
    }
 

    //---------------------------------------------------------
    # validation interface
   
    public function getRules()
    {
        $oBaseRules = parent::getRules();
        
        $oBaseRules['required'] = $oBaseRules['required'] + [['iAppointmentId']]
        
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
        return BookMeEvents::APPT_CANCELED;
    }
    

}
/* End of Clas */
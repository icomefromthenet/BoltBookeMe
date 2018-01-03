<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to Reverse a Payment Transaction 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ReversePaymentTransactionCommand  implements HasEventInterface
{
    
    protected $iAppointmentId;
    
    protected $iOrgTransacionId; 
   
    protected $oProcessingDate;
    

    public function __construct($iAppointmentId, DateTime $oProcessingDate, $iOrgTransacionId) 
    {
        $this->oProcessingDate   = $oProcessingDate;
        $this->iOrgTransacionId  = $iOrgTransacionId;
        $this->iAppointmentId    = $iAppointmentId;
    }

    
    public function getAppointmentId()
    {
        return $this->iAppointmentId;
    }
    
    public function getOrgTransactionId()
    {
        return $this->iOrgTransacionId;
    }
    
    public function getProcessingDate()
    {
        return $this->oProcessingDate;
    }
    
    
    //----------------------------------------------------------------
    # Has Event Interface
    
    public function getEvent()
    {
      return new CommandEvent($this);
    }
    
    
    public function getEventName()
    {
        return BookMeEvents::PAYMENT_TRANSACTION_REVERSED;  
    }
   
    
    
}
/* End of File */

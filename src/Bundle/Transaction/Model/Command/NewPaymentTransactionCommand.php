<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to Create a New Payment Transaction 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class NewPaymentTransactionCommand  implements HasEventInterface
{
    
    protected $iAppointmentId;
    
    protected $fCashAmount;
    
    protected $fDirectDepositAmt;
    
    protected $fCreditCardAmount;

    protected $oProcessingDate;
    


    public function __construct($iAppointmentId, $fCashAmount, $fDirectDepositAmt, $fCreditCardAmount, DateTime $oProcessingDate) 
    {
        $this->oProcessingDate      = $oProcessingDate;
        $this->fCashAmount          = $fCashAmount;
        $this->fDirectDepositAmt    = $fDirectDepositAmt;
        $this->fCreditCardAmount    = $fCreditCardAmount;
        $this->iAppointmentId       = $iAppointmentId;
    }

    
    public function getAppointmentId()
    {
        return $this->iAppointmentId;
    }
    
    public function getCashAmount()
    {
        return $this->fCashAmount;
    }
    
    public function getDirectDepositAmount()
    {
        return $this->fDirectDepositAmt;
    }
  
    public function getCreditCardAmount()
    {
        return $this->fCreditCardAmount;
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
        return BookMeEvents::PAYMENT_TRANSACTION_MADE;  
    }
   
    
    
}
/* End of File */

<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to Create a Adjustment Transaction 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class NewPaymentTransactionCommand 
{
    
    protected $iAppointmentId;
    
    protected $fAdjAmount;
    

    protected $oProcessingDate;
    


    public function __construct($iAppointmentId, $fAdjAmount, DateTime $oProcessingDate) 
    {
        $this->oProcessingDate      = $oProcessingDate;
        $this->fAdjAmount           = $fAdjAmount;
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
    
    
   
   
    
    
}
/* End of File */

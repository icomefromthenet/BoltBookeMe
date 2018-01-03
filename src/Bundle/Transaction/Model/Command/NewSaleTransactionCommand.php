<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to Create a New Sales Transaction 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class NewSaleTransactionCommand  implements HasEventInterface
{
    
    protected $iAppointmentId;
    
    protected $fTaxAmount;
    
    protected $fDiscountAmt;
    
    protected $fSalesAmount;

    protected $oProcessingDate;
    


    public function __construct($iAppointmentId, $fTaxAmount, $fSalesAmount, $fDiscountAmt, DateTime $oProcessingDate) 
    {
        $this->oProcessingDate = $oProcessingDate;
        $this->fDiscountAmt    = $fDiscountAmt;
        $this->fTaxAmount      = $fTaxAmount;
        $this->fSalesAmount    = $fSalesAmount;
        $this->iAppointmentId   = $iAppointmentId;
    }

    
    public function getAppointmentId()
    {
        return $this->iAppointmentId;
    }
    
    public function getDiscountAmount()
    {
        return $this->fDiscountAmt;
    }
    
    public function getTaxAmount()
    {
        return $this->fTaxAmount;
    }
  
    public function getSalesAmount()
    {
        return $this->fSalesAmount;
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
        return BookMeEvents::SALE_TRANSACTION_MADE;  
    }
   
    
    
}
/* End of File */

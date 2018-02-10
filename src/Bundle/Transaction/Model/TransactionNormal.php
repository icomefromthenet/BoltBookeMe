<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use DateTime;
use IComeFromTheNet\GeneralLedger\Gateway\CommonGateway;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalTransaction;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\Transaction;

/**
 * A class To create  NOT Reversal Transactions. 
 * 
 * These Transaction have occured date known ahead of time, a revesal the date is only
 * known when lookup the original transaction.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class TransactionNormal extends Transaction
{
    
    /**
     * @var DateTime
     */ 
    protected $oOccuredDate;
    


    protected function addAccountMovement($mAccountNumber, $fBalance)
    {
        $this->isValidAmount($fBalance);
        
        return $this->oJournal->addAccountMovement($mAccountNumber, $fBalance);
    }



    public function defaultBinding()
    {
        parent::defaultBinding();
        
        $this->oJournal->setOccuredDate($this->oOccuredDate);
    }
    
    
    public function __construct(
        $iAppointmentId, 
        DateTime $oProcessingDate, 
        JournalTransaction $oJournal, 
        CommonGateway $oTransactionGateway, 
        DateTime $oOccuredDate
    )
    {
        $this->oOccuredDate = $oOccuredDate;
        
        parent::__construct($iAppointmentId, $oProcessingDate, $oJournal, $oTransactionGateway);
    
    }

    /**
     * Verify allowd php number data type, don't verify the database max or min
     * here that done in the Table Gateway
     */ 
    public function isValidAmount($fAmt)
    {
        $bValid = (is_integer($fAmt) || is_float($fAmt) || is_double($fAmt));
        
        if(!$bValid) {
            throw new TransactionBundleException('The Customer Cost Amt is an invalid data type must be a number');
        }
        
        return true;
    }
    
    
     /**
     * Save the built transaction to the ledger
     * 
     * @return integer the new transaction id
     * @throws LedgerException if the transaction fails to save.
     */ 
    public function saveTransaction() 
    {
        $this->oJournal->processTransaction();
        
        return $this->oJournal->getTransactionHeader()->iTransactionID;
    }
    
   
   

}
/* End of Class */
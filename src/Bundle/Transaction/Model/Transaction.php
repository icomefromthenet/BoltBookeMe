<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use DateTime;
use IComeFromTheNet\GeneralLedger\Gateway\CommonGateway;
use IComeFromTheNet\GeneralLedger\TransactionBuilder;
use IComeFromTheNet\GeneralLedger\LedgerTransaction; 
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalTransaction;

/**
 * A Base class for Ledger Transactions. 
 * 
 * This base class will ensure that each transaction will be linked to
 * an appointment.
 * 
 * We do not bind the occured date as this not fixed if processing a reversal
 * transaction which have same occured date as the original transaction so
 * their grouped together
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class Transaction
{
    
    /**
     * @var integer the Appointment Database Id
     */ 
    protected $iAppointmentId;
    
    
    /**
     *  @var Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalTransaction
     */ 
    protected $oJournal;
    
    /**
     * @var IComeFromTheNet\GeneralLedger\Gateway\CommonGateway;
     */ 
    protected $oTransactionGateway;
    
    /**
     * @var DateTime
     */ 
    protected $oProcessingDate;
    
    /**
     * Bind this Transaction Appointment to the Journal.
     * 
     *  
     * @return void
     */ 
    protected function defaultBinding()
    {
        
        $this->oJournal->setAppointmentId($this->iAppointmentId);
        $this->oJournal->setProcessingDate($this->oProcessingDate);
       
    }
    
    
    
    public function __construct($iAppointmentId, DateTime $oProcessingDate, JournalTransaction $oJournal, CommonGateway $oTransactionGateway)
    {
     
        if(empty($iAppointmentId)) {
            throw new TransactionBundleException('The Appointment must not be empty');
        }
        
        $this->iAppointmentId       = $iAppointmentId;
        $this->oJournal             = $oJournal;
        $this->oTransactionGateway  = $oTransactionGateway;
        $this->oProcessingDate      = $oProcessingDate;
        
        $this->defaultBinding();
    }
    
    
}
/* End of File */
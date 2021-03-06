<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\VoucherNumbers;
use IComeFromTheNet\GeneralLedger\TransactionBuilder;
use IComeFromTheNet\GeneralLedger\Entity\LedgerTransaction; 
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException;

/**
 * A Base class for Transaction Journals.
 * 
 * We have Three Journals 
 * 1. Sales
 * 2. Payments
 * 3. General
 * 
 * These journals can have one to many account movements. A Sales 
 * could involve movement to the sales, tax and discount accounts.
 * 
 * Each Journal Transaction will have a unique voucher number generated
 * from the VoucherNumber Library. 
 * 
 * Each transaction must have a ledger user and this class will lookup
 * and appointments assigned user and bind to this context.
 * 
 * Note: Each appointment will have own ledger user.
 * 
 * This class can only process one journal entry, do not reuse but instance another. 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
abstract class JournalTransaction extends TransactionBuilder
{
    
    
    abstract protected function doGenerateVoucherNumber();
    
    
    abstract protected function doGetJournalType();
    
    
    protected function doSetDefault(LedgerTransaction $oAdjustedTransaction = null)
    {
        $sVoucherNumber = $this->doGenerateVoucherNumber();
        $this->setVoucherNumber($sVoucherNumber);
        
        $iJournalType = $this->doGetJournalType();
        $this->setJournalType($iJournalType);
        
        // we need a default Org unit, since where not using it
        $this->setOrgUnit(1);
        if($oAdjustedTransaction) {
            $oAdjustedTransaction->iOrgUnitID = 1;
        }
        
    }
    
    
    //-------------------------------------------------------------------------
    
    public function setAppointmentId($iApptId)
    {
        $oDatabase      = $this->getContainer()->getDatabaseAdapter();
        $aTableMap      = $this->getContainer()->getTableMap();
        $sApptTable     = $aTableMap['bm_appointment'];
        
        $iLedgerUserId = (integer) $oDatabase->fetchColumn("
            SELECT ledger_user_id 
            FROM $sApptTable 
            WHERE appointment_id = :iAppId",
            ['iAppId' => $iApptId],0
        );
        
        if(empty($iLedgerUserId)) {
            throw new TransactionBundleException('The Ledger User does not exist');
        }
        
        
        return $this->setUser($iLedgerUserId);
        
    }
    
    /**
     * Sets the Processing date
     * 
     * The General Ledger Library not like when use a date library like Carbon
     * wants a DateTime not a class that extends it.
     * 
     */ 
    public function setProcessingDate(DateTime $oNow)
    {
        $oNewDte = new DateTime($oNow);
        
        return parent::setProcessingDate($oNewDte);
    }
       
     /**
     * Sets the Occured date
     * 
     * The General Ledger Library not like when use a date library like Carbon
     * wants a DateTime not a class that extends it.
     * 
     */   
    public function setOccuredDate(DateTime $oNow)
    {
        return parent::setOccuredDate(DateTime::createFromFormat('U',$oNow->format('U')));
    }
      
    
    
    /**
     * Save the built transaction to the database using the default
     * transaction processor fetched from the DI Container
     * 
     * @return void
     * @throws LedgerException if the transaction fails to save.
     */ 
    public function processTransaction() 
    {
        $this->doSetDefault();
        
        return parent::processTransaction();   
    }
    
    /**
     * Save the built transaction to the database using the default
     * transaction processor fetched from the DI Container, Also update
     * the adjusteed transaction with the new transactions database id
     * 
     * @return void
     * @throws LedgerException if the transaction fails to save.
     * @param LedgerTransaction $oAdjustedTransaction The transaction that is being adjusted.
     */
    public function processReversal(LedgerTransaction $oAdjustedTransaction)
    {
        $this->doSetDefault($oAdjustedTransaction);
        
        return parent::processAdjustment($oAdjustedTransaction);
    }
    
    
}
/* End of Class */
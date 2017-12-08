<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\Transaction;

/**
 * A class To create reversal transaction for ALL Journal Ledgers. 
 * 
 * The DI container inject the journal context (sales,payments,discounts) we want to process
 * 
 * The occured date is only known after a lookup of the original transaction is done. 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class TransactionReverse extends Transaction
{
    
    
    protected function lookupTransaction($iTransactionId)
    {
        $oTransactionType = $this->oTransactionGateway->getMetaData()->getColumn('transaction_id')->getType();
        
        return $this
            ->oTransactionGateway
            ->selectQuery()
             ->start()
                ->where('transaction_id = :iTransactionId')
                ->setParameter(':iTransactionId',$iTransactionId,$oType)
             ->end()
           ->findOne(); 

    }  
    
    /**
     * Process a Transction Reversal for the given transaction
     *  
     * This method will do a lookup on transaction id and fetch its
     * occured date for assignment to this journal entry
     * 
     * @param integer $iTransaction The Transction Database Id
     * @return IComeFromTheNet\GeneralLedger\Entity\LedgerTransaction
     * @throws TransactionBundleException when the transaction is invalid
     */ 
    public function reverseTransaction($iTransaction)
    {
        if(!is_integer($iTransaction)) {
            throw new TransactionBundleException('Transaction Id must be an integer');
        }
        
        $oLedgerTransaction = $this->lookupTransaction($iTransaction);
        
        if(empty($oLedgerTransaction)) {
            throw new TransactionBundleException('The transaction '.$iTransaction.' Does not match an entry in ledger');
        }
        
        // Make sure this reversal has same occured date as the original, we do this
        // so the transactions are grouped together 
        $this->oJournal->setOccuredDate($oLedgerTransaction->oOccuredDate);
        
        
        $this->oJournal->processReversal($oLedgerTransaction);
        
        // This will have reversal transaction assigned to adjustment id property, return the
        // object so later process can use this identifer
        return $oLedgerTransaction;
        
    }
    
}
/* End of File */
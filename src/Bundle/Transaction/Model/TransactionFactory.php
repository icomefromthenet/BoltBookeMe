<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\Transaction;

/**
 * A Helper to fetch transaction builders from the DI Container
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class TransactionFactory
{
    
    protected $oContainer;
    
    
    protected function getLedgerTransactionGateway()
    {
        return $this
                ->oContainer
                ->getGatewayProxyCollection()
                ->getGateway('ledger_transaction');
    }
    
    
    protected function getJournal($sJournal)
    {
        return $this->oContainer['bm.transaction.journal.'.$sJournal];
    }
    
    
    
    
    public function __construct($oContainer)
    {
        $this->oContainer = $oContainer;    
    }
    
    
    /**
     * Return a Transaction Builder configured to create
     * sale journal transactions.
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\
     * @param integer   $iAppointmentId     The appointments Database Id
     * @param DateTime  $oProcessDate       The Processing date of this transaction
     * @param DateTime  $oOccuredDate       The Occured date of this transaction
     */ 
    public function salesTransaction($iAppointmentId, DateTime $oProcessDate, DateTime $oOccuredDate)
    {
        $oGatway  = $this->getLedgerTransactionGateway();
        $oJournal = $this->getJournal('sale');
        
        return new TransactionSale($iAppointmentId, $oProcessDate, $oJournal, $oGatway, $oOccuredDate);
        
    }
    
    /**
     * Return a Transaction Builder configured to create
     * payment journal transactions.
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\
     * @param integer   $iAppointmentId     The appointments Database Id
     * @param DateTime  $oProcessDate       The Processing date of this transaction
     * @param DateTime  $oOccuredDate       The Occured date of this transaction
     */ 
    public function paymentTransaction($iAppointmentId, DateTime $oProcessDate, DateTime $oOccuredDate)
    {
        $oGatway  = $this->getLedgerTransactionGateway();
        $oJournal = $this->getJournal('payment');
        
        return new TransactionPayment($iAppointmentId, $oProcessDate, $oJournal, $oGatway, $oOccuredDate);
    }
    
    
    /**
     * Return a Transaction Builder configured to create
     * general journal transactions.
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\
     * @param integer   $iAppointmentId     The appointments Database Id
     * @param DateTime  $oProcessDate       The Processing date of this transaction
     * @param DateTime  $oOccuredDate       The Occured date of this transaction
     */ 
    public function generalTransaction($iAppointmentId, DateTime $oProcessDate, DateTime $oOccuredDate)
    {
        $oGatway  = $this->getLedgerTransactionGateway();
        $oJournal = $this->getJournal('general');
        
        return new TransactionGeneral($iAppointmentId, $oProcessDate, $oJournal, $oGatway, $oOccuredDate);
        
    }
    
    
    
    //--------------------------------------------------
    # Reversales
    
    /**
     * Return a Transaction Builder configured to reverse
     * sale journal transactions.
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionReverse
     * @param integer $iAppointmentId The appointments Database Id
     * @param DateTime  $oProcessDate       The Processing date of this transaction
     */ 
    public function reverseSaleTransaction($iAppointmentId, DateTime $oProcessDate)
    {
        $oGatway  = $this->getLedgerTransactionGateway();
        $oJournal = $this->getJournal('sale');
        
        return new TransactionReverse($iAppointmentId, $oProcessDate, $oJournal, $oGatway);
      
    }
    
    /**
     * Return a Transaction Builder configured to reverse
     * payment journal transactions.
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionReverse
     * @param integer $iAppointmentId The appointments Database Id
     * @param DateTime  $oProcessDate       The Processing date of this transaction
     */ 
    public function reversePaymentTransaction($iAppointmentId, DateTime $oProcessDate)
    {
        $oGatway  = $this->getLedgerTransactionGateway();
        $oJournal = $this->getJournal('payment');

        return new TransactionReverse($iAppointmentId, $oProcessDate, $oJournal, $oGatway);
      
    }
    
    
    /**
     * Return a Transaction Builder configured to reverse
     * general journal transactions.
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionReverse
     * @param integer   $iAppointmentId     The appointments Database Id
     * @param DateTime  $oProcessDate       The Processing date of this transaction
     */ 
    public function reverseGeneralTransaction($iAppointmentId, DateTime $oProcessDate)
    {
        $oGatway  = $this->getLedgerTransactionGateway();
        $oJournal = $this->getJournal('general');
        
        return new TransactionReverse($iAppointmentId, $oProcessDate, $oJournal, $oGatway);
    }
    
    
}
/* End of File */
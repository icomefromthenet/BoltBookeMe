<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Transaction;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;


use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalGeneral;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionGeneral;


class GeneralTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
       
      $oNow      = $this->getNow();
      $oService  = $this->getTestAPI();
      $oDatabase = $this->getDatabaseAdapter();
      $aConfig   = $this->getAppConfig();
      
      return;
   }
   
   
   public function testGeneralJournalWithValidAppointment()
   {
       
       $oContainer        = $this->getContainer();
       $oLedgerContainer  = $oContainer['bm.ledger.container'];
       $iApptId           = $this->aDatabaseId['appt_customer_one_1'];
       $oNow              = $this->getNow();
       $sPaymentAccountNo = '1-0005';    

       $oPaymentJournal = new JournalGeneral($oLedgerContainer);
       
       
       $oPaymentJournal->setAppointmentId($iApptId);
       $oPaymentJournal->setProcessingDate($oNow); 
       $oPaymentJournal->setOccuredDate($oNow);
       $oPaymentJournal->addAccountMovement($sPaymentAccountNo,300);
       
       $oPaymentJournal->processTransaction();
       
       $iTransactionId = $oPaymentJournal->getTransactionHeader()->iTransactionID;
       
       $this->assertNotEmpty($iTransactionId);
       
       $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(movement) as mov 
        from bolt_bm_ledger_entry 
        where transaction_id = ?
       ",[$iTransactionId],0);
       
       
       $this->assertEquals(300, $iSum);
   }
   
   
   public function testGeneralJournalWithInvalidAppt()
   {
       $oContainer        = $this->getContainer();
       $oLedgerContainer  = $oContainer['bm.ledger.container'];
       $iApptId           = 999999;
       $oNow              = $this->getNow();
       $sPaymentAccountNo = '1-0005';    

       $oPaymentJournal = new JournalGeneral($oLedgerContainer);
       
       try {
            $oPaymentJournal->setAppointmentId($iApptId);
             $this->assertFalse(true,'Transaction should failed when invalid appt given');
       }
       catch(\Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException $e) {
           $this->assertTrue(true);
       
       }
        
       
   }
   
   
   public function testGeneralTransaction()
   {
       $oContainer        = $this->getContainer();
       $oLedgerContainer  = $oContainer['bm.ledger.container'];
       $iApptId           = $this->aDatabaseId['appt_customer_one_2'];
       $oNow              = $this->getNow();
       $oOccured          = clone $oNow;
       $oOccured->modify('- 1 day');
       
       $sCashAccountNo          = '2-0002';    
       $sDirectDepositAccountNo = '2-0004';
       $sCreditCardAccountNo    = '2-0003';
       $sSaleAccountNo          = '1-0005';    
       $sTaxAccountNo           = '1-0006';
       $sDiscountAccountNo      = '2-0010';
       
       $oTransactionGateway = $oLedgerContainer->getGatewayCollection()->getGateway('ledger_transaction');

       $oPaymentJournal = new JournalGeneral($oLedgerContainer);

       $oGeneralTran  = new TransactionGeneral($iApptId, $oNow, $oPaymentJournal, $oTransactionGateway, $oOccured);
     
       $oGeneralTran->setPaymentCash(-200);
       $oGeneralTran->setPaymentDirectDeposit(-100);
       $oGeneralTran->setPaymentCreditCard(-60);
       
       $oGeneralTran->setCustomerCost(1000);
       $oGeneralTran->setTaxOwed(100);
       $oGeneralTran->setDiscount(-50);
       
       
       $iTransactionId  = $oGeneralTran->saveTransaction();
       
       $this->assertNotEmpty($iTransactionId);
       
       
       // Verify Cash Payment
       
       $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sCashAccountNo],0);
       
       
       $this->assertEquals(-200, $iSum);
       
       // Verify Direct Deposit
       
        $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sDirectDepositAccountNo],0);
       
       
       $this->assertEquals(-100, $iSum);
       
       // Verify Credit Card 
       
       $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sCreditCardAccountNo],0);
       
       
       $this->assertEquals(-60, $iSum);
       
       
       // Verify Customer Cost
       
        $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sSaleAccountNo],0);
       
       
       $this->assertEquals(1000, $iSum);
       
       
       // Verify The Tax 
       
        $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sTaxAccountNo],0);
       
       
       $this->assertEquals(100, $iSum);
       
       
       // Verify The Discount
       
       
        $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sDiscountAccountNo],0);
       
       
       $this->assertEquals(-50, $iSum);
       
       
   }
   
}
/* End of File */
<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Transaction;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;


use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalSale;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionSale;


class SaleTest extends ExtensionTest
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
   
   
   public function testSaleJournalWithValidAppointment()
   {
       
       $oContainer        = $this->getContainer();
       $oLedgerContainer  = $oContainer['bm.ledger.container'];
       $iApptId           = $this->aDatabaseId['appt_customer_one_1'];
       $oNow              = $this->getNow();
       $sPaymentAccountNo = '1-0005';    

       $oPaymentJournal = new JournalSale($oLedgerContainer);
       
       
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
   
   
   public function testSaleJournalWithInvalidAppt()
   {
       $oContainer        = $this->getContainer();
       $oLedgerContainer  = $oContainer['bm.ledger.container'];
       $iApptId           = 999999;
       $oNow              = $this->getNow();
       $sPaymentAccountNo = '1-0005';    

       $oPaymentJournal = new JournalSale($oLedgerContainer);
       
       try {
            $oPaymentJournal->setAppointmentId($iApptId);
             $this->assertFalse(true,'Transaction should failed when invalid appt given');
       }
       catch(\Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException $e) {
           $this->assertTrue(true);
       
       }
        
       
   }
   
   public function testSaleTransaction()
   {
       $oContainer        = $this->getContainer();
       $oLedgerContainer  = $oContainer['bm.ledger.container'];
       $iApptId           = $this->aDatabaseId['appt_customer_one_2'];
       $oNow              = $this->getNow();
       $oOccured          = clone $oNow;
       $oOccured->modify('- 1 day');
       
       $sSaleAccountNo      = '1-0005';    
       $sTaxAccountNo       = '1-0006';
       $sDiscountAccountNo  = '2-0010';
       
       $oTransactionGateway = $oLedgerContainer->getGatewayCollection()->getGateway('ledger_transaction');

       $oSaleJournal = new JournalSale($oLedgerContainer);

       $oSaleTran  = new TransactionSale($iApptId, $oNow, $oSaleJournal, $oTransactionGateway, $oOccured);
     
       $oSaleTran->setCustomerCost(300);
       $oSaleTran->setTaxOwed(30);
       $oSaleTran->setDiscount(-100);
       
       $iTransactionId  = $oSaleTran->saveTransaction();
       
       $this->assertNotEmpty($iTransactionId);
       
       // Verify the Customer Cost
       
       $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sSaleAccountNo],0);
       
       
       $this->assertEquals(300, $iSum);
       
        // Verify the Tax
       
       $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sTaxAccountNo],0);
       
       
       $this->assertEquals(30, $iSum);
       
       
       // Verify the Discount
       
       $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(ae.movement) as mov 
        from bolt_bm_ledger_entry ae
        join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
        where ae.transaction_id = ?
        and ac.account_number = ?
       ",[$iTransactionId, $sDiscountAccountNo],0);
       
       
       $this->assertEquals(-100, $iSum);
       
       
   }
   
}
/* End of File */
<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Transaction;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;


use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalPayment;


class PaymentTest extends ExtensionTest
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
   
   
   public function testPaymentJournalWithValidAppointment()
   {
       
       $oContainer        = $this->getContainer();
       $oLedgerContainer  = $oContainer['bm.ledger.container'];
       $iApptId           = $this->aDatabaseId['appt_customer_one_1'];
       $oNow              = $this->getNow();
       $sPaymentAccountNo = '2-0001';    

       $oPaymentJournal = new JournalPayment($oLedgerContainer);
       
       
       $oPaymentJournal->setAppointmentId($iApptId);
       $oPaymentJournal->setProcessingDate($oNow); 
       $oPaymentJournal->setOccuredDate($oNow);
       $oPaymentJournal->addAccountMovement($sPaymentAccountNo,-700);
       
       $oPaymentJournal->processTransaction();
       
       $iTransactionId = $oPaymentJournal->getTransactionHeader()->iTransactionID;
       
       $this->assertNotEmpty($iTransactionId);
       
       $iSum = $this->getDatabaseAdapter()->fetchColumn("
        select sum(movement) as mov 
        from bolt_bm_ledger_entry 
        where transaction_id = ?
       ",[$iTransactionId],0);
       
       
       $this->assertEquals(-700, $iSum);
   }
   
   
   public function testPaymentJournalWithInvalidAppt()
   {
       $oContainer        = $this->getContainer();
       $oLedgerContainer  = $oContainer['bm.ledger.container'];
       $iApptId           = 999999;
       $oNow              = $this->getNow();
       $sPaymentAccountNo = '2-0001';    

       $oPaymentJournal = new JournalPayment($oLedgerContainer);
       
       try {
            $oPaymentJournal->setAppointmentId($iApptId);
             $this->assertFalse(true,'Transaction should failed when invalid appt given');
       }
       catch(\Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException $e) {
           $this->assertTrue(true);
       
       }
        
       
   }
   
}
/* End of File */
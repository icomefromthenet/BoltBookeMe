<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Transaction;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;


use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalPayment;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionReverse;


class ReversalTest extends ExtensionTest
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
   
   
   public function testReversePaymentTransaction()
   {
     $oContainer            = $this->getContainer();
     $oLedgerContainer      = $oContainer['bm.ledger.container'];
     $oTransactionGateway   = $oLedgerContainer->getGatewayCollection()->getGateway('ledger_transaction');
     
     $iApptId               = $this->aDatabaseId['appt_customer_one_2'];
     $oNow                  = $this->getNow();
     $iOrgTransactionId     = 5;
     $sCardPaymentAccountNo = '2-0003';    
     
     
     $oPaymentJournal = new JournalPayment($oLedgerContainer);
     
     $oRevPaymentTran  = new TransactionReverse($iApptId, $oNow, $oPaymentJournal, $oTransactionGateway);
     
     $iTransactionId  = $oRevPaymentTran->saveTransaction($iOrgTransactionId);
     
     $this->assertNotEmpty($iTransactionId);
     
     // Verify the reversal has been created 
     
     $iSum = $this->getDatabaseAdapter()->fetchColumn("
      select sum(ae.movement) as mov 
      from bolt_bm_ledger_entry ae
      join bolt_bm_ledger_account ac on ac.account_id = ae.account_id
      where ae.transaction_id = ?
      and ac.account_number = ?
     ",[$iTransactionId, $sCardPaymentAccountNo],0);
     
     
     $this->assertEquals(110, $iSum);
   
   }

   
   
   
}
/* End of File */
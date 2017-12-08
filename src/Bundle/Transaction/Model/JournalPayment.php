<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use IComeFromTheNet\GeneralLedger\TransactionBuilder;
use IComeFromTheNet\GeneralLedger\LedgerTransaction; 
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalTransaction;

/**
 * Payment Journal, used to record transactions that come from payment events
 * 
 * @since 1.0
 */ 
class JournalPayment extends JournalTransaction
{
    
    
    
  protected function doGenerateVoucherNumber()
  {
      /** @var Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\VoucherNumbers */
      $oVoucherNumbers = $this->getContainer()->getVoucherGenerator();
      
      return $oVoucherNumbers->getPaymentJournalNumber();
      
  }
  
  
  protected function doGetJournalType()
  {
      return 'payments_journal';
  }
  
    
}
/* End of Class */
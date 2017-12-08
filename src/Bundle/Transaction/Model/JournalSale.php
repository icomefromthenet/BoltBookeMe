<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use IComeFromTheNet\GeneralLedger\TransactionBuilder;
use IComeFromTheNet\GeneralLedger\LedgerTransaction; 
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalTransaction;

/**
 * Sales Journal, used to record transactions that come from sales events
 * 
 * @since 1.0
 */ 
class JournalSale extends JournalTransaction
{
    
    
    
  protected function doGenerateVoucherNumber()
  {
      /** @var Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\VoucherNumbers */
      $oVoucherNumbers = $this->getContainer()->getVoucherGenerator();
      
      return $oVoucherNumbers->getSalesJournalNumber();
      
  }
  
  
  protected function doGetJournalType()
  {
      return 'sales_journal';
  }
  
    
}
/* End of Class */
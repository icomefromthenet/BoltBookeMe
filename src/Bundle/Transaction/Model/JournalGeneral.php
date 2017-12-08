<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use IComeFromTheNet\GeneralLedger\TransactionBuilder;
use IComeFromTheNet\GeneralLedger\LedgerTransaction; 
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalTransaction;

/**
 * General Journal, used to record transactions that come from accounting adjustments events.
 * 
 * @since 1.0
 */ 
class JournalGeneral extends JournalTransaction
{
    
    
    
  protected function doGenerateVoucherNumber()
  {
      /** @var Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\VoucherNumbers */
      $oVoucherNumbers = $this->getContainer()->getVoucherGenerator();
      
      return $oVoucherNumbers->getGeneralJournalNumber();
      
  }
  
  
  protected function doGetJournalType()
  {
      return 'adjustments_journal';
  }
  
    
}
/* End of Class */
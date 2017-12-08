<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger;

use IComeFromTheNet\GeneralLedger\LedgerContainer;


/**
 * Voucher Service Container
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class CustomLedgerContainer extends LedgerContainer 
{
    protected function getDefaultTableMap() 
    {
        throw new LedgerBundleException('Must pass the table list to the container through LedgerContainer::boot');
    }
    
    /**
     * Return the voucher number generator
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\VoucherNumbers
     */ 
    public function getVoucherGenerator()
    {
        return $this['bm.voucher.generator'];
    }
    
}
/* End of Class */
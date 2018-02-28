<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger;

use IComeFromTheNet\GeneralLedger\LedgerContainer;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Provider\LedgerTableMapProvider;
use IComeFromTheNet\GeneralLedger\Provider\DBGatewayProvider;
use IComeFromTheNet\GeneralLedger\Provider\TransactionProvider;

/**
 * Voucher Service Container
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class CustomLedgerContainer extends LedgerContainer 
{
    
    
    protected function getServiceProviders()
    {
        return [
             new LedgerTableMapProvider(),
             new DBGatewayProvider(),
             new TransactionProvider(),
        ];
        
    }
    
    
    /**
     * Return the voucher number generator
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\CustomVoucherGenerator
     */ 
    public function getVoucherGenerator()
    {
        return $this['bm.voucher.generator'];
    }
    
}
/* End of Class */
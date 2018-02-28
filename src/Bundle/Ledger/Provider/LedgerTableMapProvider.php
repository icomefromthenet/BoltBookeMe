<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Provides map of bolt table names to
 * 
 * Used in LedgerContainer not BoltAppContainer (BookMeExtension).
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0 
 */ 
class LedgerTableMapProvider implements ServiceProviderInterface
{
      
    protected function getDefaultTableMap() 
    {
         return [
             # Accounts
              'ledger_account_group' => 'bolt_bm_ledger_account_group'
             ,'ledger_account'       => 'bolt_bm_ledger_account'
             
             # Journal / Org Units / Users
             ,'ledger_org_unit'      => 'bolt_bm_ledger_org_unit'
             ,'ledger_journal_type'  => 'bolt_bm_ledger_journal_type'
             ,'ledger_user'          => 'bolt_bm_ledger_user'
             
             # Transaction table
             ,'ledger_transaction'   => 'bolt_bm_ledger_transaction'
             ,'ledger_entry'         => 'bolt_bm_ledger_entry'
             ,'ledger_daily'         => 'bolt_bm_ledger_daily'
             ,'ledger_daily_org'     => 'bolt_bm_ledger_daily_org'
             ,'ledger_daily_user'    => 'bolt_bm_ledger_daily_user'
             
             
            ];
        
        
    }
  
  
  
    public function register(Container $pimple)
    {
        $pimple['table_map'] = $this->getDefaultTableMap();
    }
  
    
    
    
    
}
/* End of File */
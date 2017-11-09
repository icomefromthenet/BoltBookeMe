<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher;


use Pimple\Container;
use IComeFromTheNet\VoucherNum\VoucherContainer;


class CustomVoucherContainer extends VoucherContainer
{
    
    protected function getDefaultTableMap()
    {
        
        return array(
            self::DB_TABLE_VOUCHER_GROUP    => 'bolt_bm_voucher_group',
            self::DB_TABLE_VOUCHER_INSTANCE => 'bolt_bm_voucher_instance',
            self::DB_TABLE_VOUCHER_RULE     => 'bolt_bm_voucher_gen_rule',       
            self::DB_TABLE_VOUCHER_TYPE     => 'bolt_bm_voucher_type',
            
        );
        
    }
    
    
   
      
    
    
}
/* End of Class */ 
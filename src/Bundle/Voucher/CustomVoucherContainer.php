<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher;

use IComeFromTheNet\VoucherNum\VoucherContainer;



class CustomVoucherContainer extends VoucherContainer
{
    
    protected function getDefaultTableMap()
    {
        
        return array(
            self::DB_TABLE_VOUCHER_GROUP    => 'voucher_group',
            self::DB_TABLE_VOUCHER_INSTANCE => 'voucher_instance',
            self::DB_TABLE_VOUCHER_RULE     => 'voucher_gen_rule',       
            self::DB_TABLE_VOUCHER_TYPE     => 'voucher_type',
            
        );
        
    }
    
    
}
/* End of Class */ 
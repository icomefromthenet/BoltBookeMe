<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher;

use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherType;
use IComeFromTheNet\VoucherNum\VoucherGenerator as BaseGenerator;

/**
 * Voucher Generator, allows voucher type do setup directly,
 * the library implementation only allowd name or id and would load it directly.
 * I wanted a bulk way to load the rules to save db queries
 *
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class CustomVoucherGenerator extends BaseGenerator
{
    
    public function setVoucherType(VoucherType $oType)
    {
        return $this->loadVoucher($oType);
    }
    
    
}
/* End of Class */

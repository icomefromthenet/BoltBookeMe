<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher;

use IComeFromTheNet\VoucherNum\VoucherGenerator;

/**
 * API for generating Voucher for support journal types.
 * 
 * Using the Slug Names to identify voucher types.
 * 
 * VoucherServiceProvider registers the support vouchers.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class VoucherNumbers
{
    
    protected $aVoucherGenerators = [];
    
    
    
    public function registerVoucherGenerator($sTypeName, VoucherGenerator $oGenerator)
    {
        $this->aVoucherGenerators[$sTypeName] = $oGenerator;
    
        return $this;
        
    }
    
    /**
     * @return  VoucherGenerator
     */ 
    public function getVoucherGenerator($sTypeName)
    {
        return $this->aVoucherGenerators[$sTypeName];
    }
    
    
    //-------------------------------------------------
    // Named Voucher Generators
    
    
    public function getAppointmentNumber()
    {
        return $this->getVoucherGenerator('appointment_number')->generate();
    }
    
    
    public function getSalesJournalNumber()
    {
        return $this->getVoucherGenerator('sales_journals')->generate();
    }
    
    public function getDiscountJournalNumber()
    {
        return $this->getVoucherGenerator('discounts_journals')->generate();
    }
    
    
    public function getGeneralJournalNumber()
    {
        return $this->getVoucherGenerator('general_journals')->generate();
    }
    
}
/* End of Class */
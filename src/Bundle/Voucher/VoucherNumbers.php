<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher;

use IComeFromTheNet\VoucherNum\VoucherGenerator;


class VoucherNumbers
{
    
    protected $aVoucherGenerators = [];
    
    
    
    public function addVoucherGenerator($sTypeName, VoucherGenerator $oGenerator)
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
        return $this->getVoucherGenerator('sales')->generate();
    }
    
    
}
/* End of Class */
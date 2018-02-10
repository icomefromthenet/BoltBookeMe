<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\Builder;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderApptEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderPackageEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderCouponEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderSurchageEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\BookingEntity;

/**
 *  Order From the Web Cart
 * 
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *  @since 1.0
 */ 
class WebCatOrder
{
    
    use OrderSummaryTrait;
    
    /**
     *  @var BookingEntity
     */ 
    protected $oBooking;
   
   
    public function __construct(
        BookingEntity $oBooking,
        OrderApptEntity $oHeader, 
        OrderPackageEntity $oPackage, 
        OrderCouponEntity $oCoupon = null, 
        OrderSurchageEntity $oSurcharge = null
        )
    {
        $this->oOrderApptEntity = $oHeader;
        $this->oOrderPackage    = $oPackage;
        $this->oOrderCoupon     = $oCoupon;
        $this->oOrderSurcharge  = $oSurcharge; 
        $this->oBooking         = $oBooking;
        
    }
    
    /**
     * Gets the assigned booking for the order
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\BookingEntity
     */ 
    public function getBooking()
    {
        return $this->oBooking;
    }
    
    
    
    
}
/* End of Class */
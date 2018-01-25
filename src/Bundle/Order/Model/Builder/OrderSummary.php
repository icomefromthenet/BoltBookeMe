<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\Builder;


use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderApptEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderPackageEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderCouponEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderSurchageEntity;

/**
 *  Result of the Order Builder
 * 
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *  @since 1.0
 */ 
class OrderSummary
{
    
    protected $oOrderSurcharge;
    
    
    protected $oOrderCoupon;
    
    
    protected $oOrderPackageEntity;
    
    
    protected $oOrderApptEntity;
    
    
    /**
     * Return the Orders Surcharge
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderSurchageEntity
     */ 
    public function getOrderSurcharge()
    {
        return $this->oOrderSurcharge;
    }
    
    /**
     * Return the Orders Coupon
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderCouponEntity
     */ 
    public function getOrderCoupon()
    {
        return $this->oOrderCoupon;
    }
    
    /**
     * Return the Orders Package
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderPackageEntity
     */
    public function getOrderPackage()
    {
        return $this->oOrderPackageEntity;
    }
    
    /**
     * Return the Orders Header
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\OrderApptEntity
     */
    public function getOrderHeader()
    {
        return $this->oOrderApptEntity;
    }
    
    
    public function __construct(OrderApptEntity $oHeader, OrderPackageEntity $oPackage, OrderCouponEntity $oCoupon = null, OrderSurchageEntity $oSurcharge = null)
    {
        $this->oOrderApptEntity = $oHeader;
        $this->oOrderPackage    = $oPackage;
        $this->oOrderCoupon     = $oCoupon;
        $this->oOrderSurcharge  = $oSurcharge; 
        
    }
    
}
/* End of Class */
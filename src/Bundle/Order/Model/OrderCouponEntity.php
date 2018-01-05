<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;


/**
 * Represent an appointments order coupons in our database
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class OrderCouponEntity implements ValidationInterface
{
    
    
    public $coupon_id;
    
    public $created_on;
    
    public $updated_on;
   
    public $coupon_name;

    public $coupon_desc;

    public $coupon_cost;

    public $coupon_rate;

    public $coupon_disabled;

    public $coupon_apply_from;
    
    public $coupon_apply_to;
    
    
    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['iCouponId'],
            ]
            ,'min' => [
                ['fCouponCost',0],
                ['fCouponRate',0],
            ]
            ,'required' => [
                ['fCouponCost'],
                ['sCouponName'],
                ['fCouponRate'],
                ['oCouponApplyFrom'],
                ['oCouponApplyTo'],
            ]
            ,'boolean' => [
                ['bCouponDisabled']
            ],
            'lengthMax' => [
                ['sCouponName',100],  
                ['sCouponDesc',255],
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
            'iCouponId'         => $this->coupon_id,
            'oCreatedOn'        => $this->created_on, 
            'oUpdatedOn'        => $this->updated_on,
            'sCouponName'       => $this->coupon_name,
            'sCouponDesc'       => $this->coupon_desc,
            'fCouponCost'       => $this->coupon_cost,
            'fCouponRate'       => $this->coupon_rate,
            'bCouponDisabled'   => $this->coupon_disabled,
            'oCouponApplyFrom'  => $this->coupon_apply_to,
            'oCouponApplyTo'    => $this->coupon_apply_to,

        ];
    }
    
    
    //------------------------------------------
    # Properties
    
    
    public function getCouponId()
    {
        return $this->coupon_id;
    }
    
    public function getCouponName()
    {
        return $this->coupon_name;
    }
    
    public function getCouponDescription()
    {
        return $this->coupon_desc;
    }
    
    public function getCouponCost()
    {
        return $this->coupon_cost;
    }
    
    public function getCouponRate()
    {
        return $this->coupon_rate;
    }
    
    public function getIsCouponDisabled()
    {
        return $this->coupon_disabled;
    }
    
    public function getCouponApplyFrom()
    {
        return $this->coupon_apply_from;
    }
    
    public function getCouponApplyTo()
    {
        return $this->coupon_apply_to;
    }
    
    public function getCreatedOn()
    {
        return $this->created_on;
    }
    
    public function getUpdatedOn()
    {
        return $this->updated_on;
    }
    
}
/* End of customer */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;


/**
 * Represent an appointments order in our database
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class OrderApptEntity implements ValidationInterface
{
    
    public $order_id;
    
    public $appointment_id;
    
    public $created_on;
    
    public $updated_on;
    
    public $is_deposit_only;
    
    public $deposit_amt;
    
    public $deposit_rate;
    
    public $package_cost;
    
    public $discount_cost;
    
    public $surcharge_cost;
    
    public $tax_cost;
     
    
    protected function doCalculateCustomerCost()
    {
        if($this->is_deposit_only) {
            return $this->deposit_amt;
        }
        
        return $this->doCalculateSaleTotal(); 
    }
    
    protected function doCalculateSaleTotal()
    {
        return (($this->package_cost + $this->surcharge_cost) - $this->discount_cost);
    }
    
    
    protected function doCalculateOutstanding()
    {
         return $this->doCalculateCustomerCost() + $this->tax_cost;
    }
    
    
    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['iAppointmentId'],
            ]
            ,'min' => [
                ['iAppointmentId',1],
                ['fPackageCost',0],
                ['fSurchargeCost',0],
                ['fTaxCost',0],
                ['fDepositAmt',0],
                ['fDepositRate',0],
            ],
            'max' => [
                ['fDiscountCost',0],
            ]
            ,'required' => [
                ['iAppointmentId'],
                ['fPackageCost'],
                ['fDiscountCost'],
                ['fSurchargeCost'],
                ['fTaxCost'],
                ['fDepositAmt'],
                ['fDepositRate']
            ]
            ,'boolean' => [
                ['bIsDepositOnly']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
             'iAppointmentId'   => $this->appointment_id,
             'oCreatedOn'       => $this->created_on, 
             'oUpdatedOn'       => $this->updated_on,
             'bIsDepositOnly'   => $this->is_deposit_only,
             'fDepositAmt'      => $this->deposit_amt,
             'fDepositRate'     => $this->deposit_rate,
             'fPackageCost'     => $this->package_cost,
             'fDiscountCost'    => $this->discount_cost,
             'fSurchargeCost'   => $this->surcharge_cost,
             'fTaxCost'         => $this->tax_cost
                    
        ];
    }
    
    
    //------------------------------------------
    # Properties
    
    
    public function getAppointmentId()
    {
        return $this->appointment_id;
    }
    
    public function getCreatedOn()
    {
        return $this->created_on;
    }
    
    public function getUpdatedOn()
    {
        return $this->updated_on;
    }
    
    public function getTaxCost()
    {
        return $this->tax_cost;
    }
    
    public function getDiscountCost()
    {
        return $this->discount_cost;
    }
    
    public function getPackageCost()
    {
        return $this->package_cost;
    }
    
    public function getSurchargeCost()
    {
        return $this->getSurchargeCost();
    }
    
    public function getDeposit()
    {
        return $this->deposit_amt;
    }
    
    public function getDepositRate()
    {
        return $this->deposit_rate;
    }
    
    /**
     * This return the customer owes on this order without tax.
     * 
     * If this order only requires the deposit then return the
     * deposit value, and full value required it return the orders sale value
     * 
     *  Not tax and no payments.
     * 
     * @return  float   The amount customer required to pay of thus order
     * 
     */ 
    public function getCustomerCost()
    {
        return $this->doCalculateCustomerCost();
    }
    
    /**
     * This calcualtes the sales total for this order
     * 
     * This is the package (cost + surcharge) - discounts
     * 
     * No Tax included here.
     * 
     * @return  float   The Sales Total of this order
     */ 
    public function getSaleTotal()
    {
        return $this->doCalculateSaleTotal();
    }
    
    /**
     *  Fetch the Total Outstanding for this order
     * 
     *  This is the Customer Cost + Tax, 
     * 
     * @return  float   What the customer need to pay for this order 
     * 
     */ 
    public function getTotalOutstanding()
    {
        return $this->doCalculateOutstanding();
    }
}
/* End of customer */
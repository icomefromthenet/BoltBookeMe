<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;


/**
 * Represent an appointments order surcharges in our database
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class OrderSurchargeEntity implements ValidationInterface
{
    
    
    public $surcharge_id;
    
    public $created_on;
    
    public $updated_on;
   
    public $surchage_name;

    public $surchage_desc;

    public $surchage_cost;

    public $surchage_rate;

    public $surcharge_disabled;

    public $rule_id;
    
    
    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['iSurchargeId'],
                ['iSurchargeRuleId'],
            ]
            ,'min' => [
                ['fSurchargeCost',0],
                ['fSurchargeRate',0],
            ]
            ,'required' => [
                ['fSurchargeCost'],
                ['sSurchargeName'],
                ['fSurchargeRate'],
                ['iSurchargeRuleId'],
            ]
            ,'boolean' => [
                ['bSurchargeDisabled']
            ],
            'lengthMax' => [
                ['sSurchargeName',100],  
                ['sSurchargeDesc',255],
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
            'iSurchargeId'       => $this->surcharge_id,
            'oCreatedOn'         => $this->created_on, 
            'oUpdatedOn'         => $this->updated_on,
            'sSurchargeName'     => $this->surcharge_name,
            'sSurchargeDesc'     => $this->surcharge_desc,
            'fSurchargeCost'     => $this->surcharge_cost,
            'fSurchargeRate'     => $this->surcharge_rate,
            'bSurchargeDisabled' => $this->surcharge_disabled,
            'iSurchargeRuleId'   => $this->rule_id
        ];
    }
    
    
    //------------------------------------------
    # Properties
    
    
    public function getSurchageId()
    {
        return $this->surcharge_id;
    }
    
    public function getSurchargeName()
    {
        return $this->surchage_name;
    }
    
    public function getSurchargeDescription()
    {
        return $this->surchage_desc;
    }
    
    public function getSurchargeCost()
    {
        return $this->surchage_cost;
    }
    
    public function getSurchargeRate()
    {
        return $this->surchage_rate;
    }
    
    public function getIsSurchargeDisabled()
    {
        return $this->surcharge_disabled;
    }
    
    public function getSurchargeRuleId()
    {
        return $this->rule_id;
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
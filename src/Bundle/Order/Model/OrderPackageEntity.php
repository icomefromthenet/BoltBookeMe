<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;


/**
 * Represent an appointments order package in our database
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class OrderPackageEntity implements ValidationInterface
{
    
    
    public $package_id;
    
    public $created_on;
    
    public $updated_on;
   
    public $package_name;

    public $package_desc;

    public $package_cost;

    public $package_tax_rate;

    public $package_disabled;

    
    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['iPackageId'],
            ]
            ,'min' => [
                ['fPackageCost',0],
                ['fPackageTaxRate',0],
            ]
            ,'required' => [
                ['fPackageCost'],
                ['sPackageName'],
                ['fPackageTaxRate'],
            ]
            ,'boolean' => [
                ['bPackageDisabled']
            ],
            'lengthMax' => [
                  ['sPackageName',100],  
                  ['sPackageDesc',255],
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
            'iPackageId'    => $this->package_id,
            'oCreatedOn'    => $this->created_on, 
            'oUpdatedOn'    => $this->updated_on,
            'sPackageName'  => $this->package_name,
            'sPackageDesc'  => $this->package_desc,
            'fPackageCost'     => $this->package_cost,
            'fPackageTaxRate'  => $this->package_tax_rate,
            'bPackageDisabled' => $this->package_disabled,

        ];
    }
    
    
    //------------------------------------------
    # Properties
    
    
    public function getPackageId()
    {
        return $this->package_id;
    }
    
    public function getPackageName()
    {
        return $this->package_name;
    }
    
    public function getPackageDescription()
    {
        return $this->package_desc;
    }
    
    public function getPackageCost()
    {
        return $this->package_cost;
    }
    
    public function getPackageTaxRate()
    {
        return $this->package_tax_rate;
    }
    
    public function getIsPackageDisabled()
    {
        return $this->package_disabled;
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
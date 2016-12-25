<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;


/**
 * Represent a customer in our database
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class CustomerEntity implements ValidationInterface
{
    
    public $iCustomerId;
    
    public $oCreatedDate;
    
    public $oLastUpdateDate;

    public $sFirstName;
    
    public $sLastName;
    
    public $sEmail;
    
    public $sMobile;
    
    public $sLandline;
    
    public $sAddressLineOne;
    
    public $sAddressLineTwo;
    
    public $sCompanyName;
    
    
    public function __set($name,$value) 
    {
        switch($name) {
          case 'customer_id': 
            $this->iCustomerId = $value;
          break;
          
          case 'created_on':
              $this->oCreatedDate = $value;
          break; 
          
          case 'updated_on':
              $this->oLastUpdateDate = $value;
          break; 
          
          case 'first_name':
              $this->sFirstName  = $value;
          break; 
          
          case 'last_name':
              $this->sLastName = $value;
          break;
          
          case 'email':
            $this->sEmail = $value;
          break;     
          
          case 'mobile':
              $this->sMobile = $value;
          break;
          
          case 'landline': 
              $this->sLandline = $value;
          break;
          
          case 'address_one': 
              $this->sAddressLineOne = $value;
          break;
          
          case 'address_two':
              $this->sAddressLineTwo = $value;
          break;
         
          case 'company_name':
              $this->sCompanyName = $value;
          break;
          default: 
            $this->{$name} = $value;  
            
        }     
    
    }
    
    
    public function getCustomerId()
    {
        return $this->iCustomerId;
    }
    
    public function getCreatedDate()
    {
        return $this->oCreatedDate;
    }
    
    public function getLastUpdateDate()
    {
        return $this->oLastUpdateDate;
    }
    
    public function getFirstName()
    {
        return $this->sFirstName;
    }
    
    public function getLastName()
    {
        return $this->sLastName;
    }
    
    public function getEmail()
    {
        return $this->sEmail;
    }
    
    public function getMobile()
    {
        return $this->sMobile;
    }
    
    public function getLandLine()
    {
        return $this->sLandline;
    }
    
    public function getCompanyName()
    {
        return $this->sCompanyName;
    }
    
    public function getAddressLineOne()
    {
        return $this->sAddressLineOne;
    }
    
    public function getAddressLineTwo()
    {
        return $this->sAddressLineTwo;
    }
    
    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['iCustomerId']
            ]
            ,'min' => [
                ['iCustomerId',1]
            ]
            ,'lengthMax' => [
                ['sFirstName',100], ['sLastName',100], ['sMobile',20], ['sLandline',20], ['sAddressLineOne',100],
                ['sAddressLineTwo',100], ['sCompanyName',100]
            ]
            ,'instanceOf' => [
                ['oCreatedDate','DateTime'], ['oLastUpdateDate','DateTime']    
            ]
            ,'required' => [
                ['sFirstName'],['sLastName']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
             'iCustomerId'      => $this->iCustomerId
            ,'oCreatedDate'     => $this->oCreatedDate
            ,'oLastUpdateDate'  => $this->oLastUpdateDate
            ,'sFirstName'       => $this->sFirstName
            ,'sLastName'        => $this->sLastName
            ,'sEmail'           => $this->sEmail
            ,'sMobile'          => $this->sMobile
            ,'sLandline'        => $this->sLandline
            ,'sAddressLineOne'  => $this->sAddressLineOne
            ,'sAddressLineTwo'  => $this->sAddressLineTwo
            ,'sCompanyName'     => $this->sCompanyName
        ];
    }
    
    //-------------------------------------------------------------
    # Legacy Fields
    
    protected $sContentType;
    
    public function getContenttype() {
        return $this->sContentType;
    }
    
    public function setContenttype($sType) {
        $this->sContentType = $sType;
    }
    
}
/* End of customer */
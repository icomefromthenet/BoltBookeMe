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
    
}
/* End of customer */
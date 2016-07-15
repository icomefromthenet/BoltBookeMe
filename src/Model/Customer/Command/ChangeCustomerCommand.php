<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerEntity;


/**
 * This command is used to update on customer record
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ChangeCustomerCommand  extends CustomerEntity implements HasEventInterface, ValidationInterface
{

    
    public function __construct($iCustomerId, $sFirstName, $sLastName, $sEmail, $sMobile, $sLandline, $sAddressLineOne, $sAddressLineTwo, $sCompanyName)
    {
       $this->sFirstName        = $sFirstName;
       $this->sLastName         = $sLastName;
	   $this->sEmail            = $sEmail; 
	   $this->sMobile           = $sMobile;
	   $this->sLandline         = $sLandline;
	   $this->sAddressLineOne   = $sAddressLineOne;
	   $this->sAddressLineTwo   = $sAddressLineTwo;
	   $this->sCompanyName      = $sCompanyName;
	   $this->iCustomerId       = $iCustomerId;
    }
    
    
    public function getCustomerId()
    {
        return $this->iCustomerId;
    }
    
    
    //---------------------------------------------------------
    # Validation interface
   
    public function getRules()
    {
        $oBaseRules = parent::getRules();
        
        $oBaseRules['required'] = $oBaseRules['required'] + [['iCustomerId']]
        
        return $oBaseRules;
    }
    
    
    //----------------------------------------------------------------
    # Has Event Interface
    
    public function getEvent()
    {
      return new CommandEvent($this);
    }
    
    
    public function getEventName()
    {
        return BookMeEvents::CUSTOMER_UPDATE;  
    }
    

}
/* End of Clas */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerEntity;


/**
 * This command is used to create a customer record
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateCustomerCommand extends CustomerEntity implements  HasEventInterface
{

    public function __construct($sFirstName, $sLastName, $sEmail, $sMobile, $sLandline, $sAddressLineOne, $sAddressLineTwo, $sCompanyName)
    {
       $this->sFirstName        = $sFirstName;
       $this->sLastName         = $sLastName;
	   $this->sEmail            = $sEmail; 
	   $this->sMobile           = $sMobile;
	   $this->sLandline         = $sLandline;
	   $this->sAddressLineOne   = $sAddressLineOne;
	   $this->sAddressLineTwo   = $sAddressLineTwo;
	   $this->sCompanyName      = $sCompanyName;
	   
    }
    
    
    public function getCustomerId()
    {
        return $this->iCustomerId;
    }
    
 
    
    //----------------------------------------------------------------
    # Has Event Interface
    
    public function getEvent()
    {
      return new CommandEvent($this);
    }
    
    
    public function getEventName()
    {
        return BookMeEvents::CUSTOMER_CREATE;  
    }
    
   
}
/* End of Clas */
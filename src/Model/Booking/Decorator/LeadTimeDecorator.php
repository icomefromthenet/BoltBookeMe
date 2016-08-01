<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Decorator;

use DateTime;
use DateInterval;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\TakeBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\BookingException;


/**
 * Used stop booking being taken before the member is ready to proceed with the booking
 *
 * If the lead time is in hours and minutes, seconds then we check to ensure the appointment
 * is not created before that time, however if the lead time is greater then 1 calendar day
 * then the check will ensure that the booking can not be taken on same day.
 * 
 * if had a lead time of 3 days no booking could be taken now-3 days. If the lead time was only 12 hours
 * the check would block booking now-12hours. 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class LeadTimeDecorator
{
    
    /**
     * @var array   a map internal table names to external names
     */ 
    protected $aTableNames;
    
    /**
     * @var Doctrine\DBAL\Connection    the database adpater
     */ 
    protected $oDatabaseAdapter;
    
    
    protected $oHandler;
    

    public function __construct($oInternalHander ,array $aTableNames, Connection $oDatabaseAdapter)
    {
        $this->oDatabaseAdapter = $oDatabaseAdapter;
        $this->aTableNames      = $aTableNames;
        $this->oHandler         = $oInternalHander;   
    }
    
    
    public function handle(TakeBookingCommand $oCommand)
    {
        $oDatabase      = $this->oDatabaseAdapter;
        $oLeadTime      = $oCommand->getLeadTime();
        $iSeconds       = date_create('@0')->add($oLeadTime)->getTimestamp();
        $oCheck         = clone $oCommand->getOpeningSlot();    
        $oBoundry       = clone $oCommand->getNow();
        
          
        // find the opening boundry of the required lead time
        if($iSeconds >= (60*60*24)) {
            // More then 1 calendar day     
            $iDays = ceil($iSeconds/(60*60*24));
            $oBoundry->modify("-$iDays Day");
            
            // compare days not time so zero time down
            $oCheck->setTime(0,0,0);
            $oBoundry->setTime(0,0,0);
          
            
        } else {
            // Less then 1 calendar day
            $oCheck->modify("-$iSeconds Second");
        }
        
        // Check if where booking inside lead time 
        if($oBoundry < $oCheck) {
            throw BookingException::hasFailedLeadTimeCheck($oCommand);
        }
        
        
        return $this->oHandler->handle($oCommand);
        
    }
     
    
}
/* End of File */
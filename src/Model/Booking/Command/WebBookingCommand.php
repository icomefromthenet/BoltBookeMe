<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command;

use DateTime;
use DateInterval;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to schedule a web booking a booking done via website
 * that will require both a maxbooking and a lead time check.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class WebBookingCommand  extends TakeBookingCommand 
{

    
    /**
     * @var Number of max bookings per calendar day
     */ 
    protected $iMaxBookings;
    
    
    /**
     * @var DateTime the current datetime
     */ 
    protected $oNow;
    
    /**
     * @var DateInterval the length of the leadtime
     */ 
    protected $oLeadTime;
    
    
    
    public function __construct($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot,  DateTime $oNow,  $iMaxBookings = null, DateInterval $oLeadTime )
    {
        
        $this->iMaxBookings = $iMaxBookings;
        $this->oLeadTime    = $oLeadTime;
        $this->oNow         = $oNow;
        
        parent::__construct($iScheduleId,$oOpeningSlot,$oClosingSlot,$iMaxBookings);   
    }


    /**
     * Fetches the max number of allowed bookings if check required
     * 
     * @access public
     */ 
    public function getMaxBookings()
    {
        return $this->iMaxBookings;
    }
    
    /**
     * Fetches the leadtime
     * 
     * @access public
     * @return DateInterval
     */ 
    public function getLeadTime()
    {
        return $this->oLeadTime;
    }
    
    /**
     * Fetches the now date
     * 
     * @access public
     * @return DateTime
     */ 
    public function getNow()
    {
        return $this->oNow;
    }

}
/* End of Clas */
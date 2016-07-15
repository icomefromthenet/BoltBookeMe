<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to schedule a booking
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ClearBookingCommand implements  HasEventInterface, ValidationInterface
{

    /**
     * @var integer bookings database id once scheduled
     */ 
    protected $iBookingDatabaseId;

    
    
    public function __construct($iBookingDatabaseId)
    {
        $this->iBookingDatabaseId      = $iBookingDatabaseId;
        
    }
    
    
    /**
     * Fetches the database id of the booking once sucessfuly taken
     * 
     * @access public
     */ 
    public function getBookingId()
    {
        return $this->iBookingDatabaseId;
    }
    
    
    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['booking_id']
            ]
            ,'min' => [
                ['booking_id',1]
            ]
            ,'required' => [
                ['booking_id']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
            'booking_id'   => $this->iBookingDatabaseId,
        ];
    }
    
    //----------------------------------------------------------------
    # Has Event Interface
    
    public function getEvent()
    {
      return new CommandEvent($this);
    }
    
    
    public function getEventName()
    {
        return BookMeEvents::BOOKING_CLEARED;  
    }
    

}
/* End of Clas */
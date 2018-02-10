<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;


/**
 * Represent a customer in our database
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class BookingEntity 
{
    
   /**
     * @var integer bookings database id once scheduled
     */ 
    protected $iBookingDatabaseId;

    /**
     * @var integer is the schedule instance to join
     */ 
    protected $iScheduleId;
    
    /**
     * @var Datetime the opening slot in the schedule
     */ 
    protected $oOpeningSlot;
    
    /**
     * @var DateTime the closing slot in the schedule
     */ 
    protected $oClosingSlot;
    
    /**
     * @var DateTime Register Date
     */ 
    protected $oRegisteredDate;
    
    
    /**
     * Fetches the opening slot in this booking
     * 
     * @access public
     * @return DateTime     
     */ 
    public function getOpeningSlot()
    {
        return $this->oOpeningSlot;
    }
    
    
    /**
     * Fetches theclosing slot in this booking
     * 
     * @access public
     * @return Datetime 
     */ 
    public function getClosingSlot()
    {
        return $this->oClosingSlot;
    }
    
   
    
    /**
     * Fetches the database id of the schedule to use
     * 
     * @access public
     */ 
    public function getScheduleId()
    {
        return $this->iScheduleId;
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
    
    /**
     *  Returns the Date the booking was created
     * 
     * @return DateTime 
     */ 
    public function getBookingCreatedDate()
    {
        return $this->oRegisteredDate;
    }
    
    
    public function getData()
    {
        return [
            'booking_id'   => $this->iBookingDatabaseId,  
            'opening_slot' => $this->oOpeningSlot,
            'closing_slot' => $this->oClosingSlot,
            'schedule_id'  => $this->iScheduleId,
            'created_date' => $this->oRegisteredDate
        ];
    }
  
  
     
    public function __set($name,$value) 
    {
        switch($name) {
          case 'booking_id': 
            $this->iBookingDatabaseId = $value;
          break;
          
          case 'opening_slot':
              $this->oOpeningSlot = $value;
          break; 
          
          case 'closing_slot':
              $this->oClosingSlot = $value;
          break; 
          
          case 'schedule_id':
              $this->iScheduleId  = $value;
          break; 
          
          case 'created_date':
              $this->oRegisteredDate = $value;
          break;
          default:           
            $this->{$name} = $value;  
            
        }     
    
    }
    
    
    //-------------------------------------------------------------
    # Legacy Fields
    
    protected $sContentType;
    
    public function getContenttype()
    {
        return $this->sContentType;
    }
    
    public function setContenttype($sType)
    {
        $this->sContentType = $sType;
    }
    
}
/* End of customer */
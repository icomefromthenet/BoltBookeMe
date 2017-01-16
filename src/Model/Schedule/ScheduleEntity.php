<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule;

/**
 * A Schedule Entity
 * 
 * Maps to bm_schedule database table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class ScheduleEntity
{
    
    public $schedule_id;
    public $timeslot_id;
    public $membership_id; 
    public $calendar_year;
    public $is_carryover;
    public $registered_date;
    public $close_date;
    
    // Virtual Fields
    public $member_name;
    public $member_email;
    
    //--------------------------------------------------------------
    # Getters and Setters
    
    
    
    public function getScheduleId()
    {
        return $this->schedule_id;
    }
    
    
    public function getTimeslotId()
    {
        return $this->timeslot_id;
    }
    
    
    public function getMemberId()
    {
        return $this->membership_id;
    }
    
   
    public function getCalendarYear()
    {
        return $this->calendar_year;
    }
    
    public function getCarryOver()
    {
        return $this->is_carryover;
    }
    
    public function getRegisteredDate()
    {
        return $this->registered_date;
    }
    
    public function getCloseDate()
    {
        return $this->close_date;
    }
    
    
    public function getMemberName()
    {
        return $this->member_name;
    }
    
    
    public function getMemberEmail()
    {
        return $this->member_email;
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
/* End of Class */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup;

/**
 * A Timeslot Entity
 * 
 * Maps to bm_timeslot database table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class TimeslotEntity
{
    
    public $timeslot_id;
    
    public $timeslot_length; 
    
    public $is_active_slot;
    
    
    //--------------------------------------------------------------
    # Getters and Setters
    
   
    public function getTimeslotId()
    {
        return $this->timeslot_id;
    }
    
    public function getSlotLength()
    {
        return $this->timeslot_length;
    }
    
    public function getActiviteStatus()
    {
        return (boolean) $this->is_active_slot;
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
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member;

/**
 * A Schedule Member Entity
 * 
 * Maps to bm_schedule_membership database table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class MemberEntity
{
    
    public $membership_id;
    
    public $registered_date;
    
    public $member_name;
    
    //--------------------------------------------------------------
    # Getters and Setters
    
    
    
    public function getMembershipId()
    {
        return $this->membership_id;
    }
    
    public function getMemberId()
    {
        return $this->membership_id;
    }
    
    public function getRegisteredDate()
    {
        return $this->registered_date;
    }
    
    public function getMemberName()
    {
        return $this->member_name;
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
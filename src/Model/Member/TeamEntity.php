<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member;

/**
 * A Schedule Team Entity
 * 
 * Maps to bm_schedule_membership database table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class TeamEntity
{
    
    public $team_id;
    
    public $registered_date;
    
    public $team_name;
    
    //--------------------------------------------------------------
    # Getters and Setters
    
    
    public function getTeamId()
    {
        return $this->team_id;
    }
    
    
    public function getRegisteredDate()
    {
        return $this->registered_date;
    }
    
    
    public function getTeamName()
    {
        return $this->team_name;
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
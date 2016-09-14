<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule;

/**
 * A Schedule Rule Entity
 * 
 * Maps to bm_rule database table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class RuleEntity
{
    public $rule_desc;
    public $rule_id;
    public $rule_type_id;
    public $timeslot_id;
    public $repeat_dayofweek;
    public $repeat_dayofmonth;
    public $repeat_month;
    public $repeat_hour;
    public $repeat_minute;
    public $repeat_weekofyear;
    public $start_from;
    public $end_at;
    public $open_slot;
    public $close_slot;
    public $cal_year;
    public $is_single_day;
    
    
    //--------------------------------------------------------------
    # Getters and Setters
    
    
    
    public function getRuleId()
    {
        return $this->rule_id;
    }
    
    
    public function getRuleTypeId()
    {
        return $this->rule_type_id;
    }
    
    public function getTimeslotId()
    {
        return $this->timeslot_id;
    }
    
    
    public function getRepeatDayOfWeek()
    {
        return $this->repeat_dayofweek;
    }
    
    
    public function getRepeatDayOfMonth()
    {
        return $this->repeat_dayofmonth;
    }
    
    
    public function getRepeatMonth()
    {
        return $this->repeat_month;
    }
    
    public function getRepeatWeekOfYear()
    {
        return $this->repeat_weekofyear;
    }
    
    public function getStartFrom()
    {
        return $this->start_from;
    }
    
    public function getEndAt()
    {
        return $this->end_at;
    }
    
    
    public function getDayOpenSlot()
    {
        return $this->open_slot;
    }
    
    
    public function getDayCloseSlot()
    {
        return $this->close_slot;
    }
    
    
    public function getCalendarYear()
    {
        return $this->cal_year;
    }
    
    
    public function getSingleDayFlag()
    {
        return $this->is_single_day;
    }
    
    
    public function getRuleName()
    {
        return $this->rule_name;
    }
    
    
    public function getRuleDescription()
    {
        return $this->rule_desc;
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
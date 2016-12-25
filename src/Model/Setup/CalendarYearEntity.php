<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup;

/**
 * A Calendar Year Entity
 * 
 * Maps to bm_calendar_years database table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class CalendarYearEntity
{
    
    public $y;
    
    public $y_start;
    
    public $y_end;
    
    public $current_year;
    
    //--------------------------------------------------------------
    # Getters and Setters
    
   
    public function getCalendarYear()
    {
        return $this->y;
    }
    
    public function getStartOfYear()
    {
        return $this->y_start;
    }
    
    public function getEndOfYear()
    {
        return $this->y_end;
    }
    
    public function getCurrentYearFlag()
    {
        return (bool) $this->current_year;
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
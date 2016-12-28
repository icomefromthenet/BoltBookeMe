<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule;

/**
 * A Schedule Rule Type Entity
 * 
 * Maps to bm_rule_type database table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class RuleTypeEntity
{
    public $rule_type_id;
    public $rule_code;
    public $is_work_day;
    public $is_exclusion;
    public $is_inc_override;
   
    
    
    //--------------------------------------------------------------
    # Getters and Setters
    

    public function getRuleTypeId()
    {
        return $this->rule_type_id;
    }
    
    public function getRuleTypeCode()
    {
        return $this->rule_code;
    }
    
    
    public function getWorkDayFlag()
    {
        return $this->is_work_day;
    }
    
    
    public function getExclusionFlag()
    {
        return $this->is_exclusion;
    }
    
    
    public function getInclusionOverrideFlag()
    {
        return $this->is_inc_override;
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
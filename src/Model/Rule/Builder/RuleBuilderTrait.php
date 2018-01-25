<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleEntity;

/**
 * Defines the interface to a  rule builder.
 * 
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
trait RuleBuilderTrait
{
    /**
     *  @var Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleEntity
     */ 
    protected $oSchedule;
    
    /**
     * @var DateTime 
     */ 
    protected $oStartFromDate;
    
    /**
     * @var DateTime
     */ 
    protected $oEndtAtDate;
    
    /**
     * @var DateTime
     */ 
    protected $oStartingTime;
    
    /**
     * @var DateTime
     */ 
    protected $oEndtAtTime;
    
    /**
     *  @var string
     */ 
    protected $sRuleName;
    
    /**
     *  @var string
     */ 
    protected $sRuleDesc;
    
    
    public function forSchedule(ScheduleEntity $oSchedule)
    {
        $this->oSchedule = $oSchedule;
        
        return $this;
    }
    
    
    public function forDateBetween(DateTime $oStartDate, DateTime $oEndDate)
    {
        $this->oStartFromDate = $oStartDate;
        $this->oEndtAtDate = $oEndDate;
        
        return $this;    
    }


    public function forTimeBetween(DateTime $oStartingTime, DateTime $oEndingTime)
    {
        $this->oStartingTime = $oStartingTime;
        $this->oEndtAtTime = $oEndingTime;
        
        return $this;
    }
    

    public function forRulename($sRuleName)
    {
       $this->sRuleName = $sRuleName;
       
       return $this;
    }
   
   
    public function forRuleDescription($sRuleDescription)
    {
       $this->sRuleDesc = $sRuleDescription;
       
       return $this;
    }

    
}
/* End of Trait */
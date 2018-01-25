<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\Rule;

use DateTime;
use Doctrine\DBAL\Connection;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule;

/**
 * Defines the interface to a surcharge rule.
 * 
 * These classes will convert runtime options into new Schedule Rule Implementations.
 * Mixture of a Factory and a Template.
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
abstract class CommonSurchrgeRule 
{
    
    
    protected $oSchedule;
    protected $oStartFromDate;
    protected $oEndtAtDate;
    
    
    protected $oDatabase;
    protected $aTableNames;
 
 
    public function __construct(Connection $oDatabase, $aTableTables)
    {
        $this->oDatabase  = $oDatabase;
        $this->aTableNames = $aTableTables;
        
    }
 
 
    protected function matchSlotsToDate()
    {
        $aTableTables       = $this->aTableNames;
        $oDatabase          = $this->oDatabase;
        $iScheduleId        = $this->oSchedule->getScheduleId();
        $oOpeningDate       = clone $this->oStartFromDate;
        $oEndingDate        = clone $this->oEndtAtDate;
        
        $sUserScheduleTable = $aTableTables['bm_schedule_slot'];
        
        // Make sure not time component
        $oOpeningDate->setTime(0,0,0);
        $oEndingDate->setTime(0,0,0);
        
        // Run Query
        
        $oDatabase->$fetchAll('',[':iScheduleId' => $iScheduleId, ':oOpenSlot' => $oOpeningDate, ':oCloseSlot'])
    }
    
    
    public function forSchedule(Schedule $oSchedule)
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

    
    
    public function getNewRuleCommand()
    {
        
        $oStartFromDate      = $this->oStartFromDate;
        $oEndtAtDate         = $this->oEndtAtDate;
        $iRuleTypeDatabaseId = 5;
        $iTimeslotDatabaseId = $this->oSchedule->getTimeslotId();
        $iOpeningSlot
                              , $iClosingSlot
                              , $sRepeatDayofweek
                              , $sRepeatDayofmonth
                              , $sRepeatMonth
                              , $sRepeatWeekofyear
                              , $bIsSingleDay = false
                              , $sRuleName  = null
                              , $sRuleDescription = null
        
        return new CreateRuleCommand(DateTime $oStartFromDate
                              , DateTime $oEndtAtDate
                              , $iRuleTypeDatabaseId
                              , $iTimeslotDatabaseId
                              , $iOpeningSlot
                              , $iClosingSlot
                              , $sRepeatDayofweek
                              , $sRepeatDayofmonth
                              , $sRepeatMonth
                              , $sRepeatWeekofyear
                              , $bIsSingleDay = false
                              , $sRuleName  = null
                              , $sRuleDescription = null);    
    }
    
}
/* End of Class */
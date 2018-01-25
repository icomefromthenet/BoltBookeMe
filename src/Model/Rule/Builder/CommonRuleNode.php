<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleEntity;

/**
 * Defines the interface to a  rule builder.
 * 
 * These classes will convert runtime options into new Schedule Rule Implementations.
 * Mixture of a Factory and a Template.
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
abstract class CommonRuleNode
{
    
    use RuleBuilderTrait;
    
    
    
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
        $oOpeningDate       = clone $this->oStartingTime;
        $oEndingDate        = clone $this->oEndtAtTime;
        
        $sTimeslotDayTable   = $aTableTables['bm_timeslot_day'];
        $sUserScheduleTable  = $aTableTables['bm_schedule'];
        
        $iOpenSlot  = (($oOpeningDate->format('G') * 60) + $oOpeningDate->format('i')); 
        $iCloseSlot = (($oEndingDate->format('G') * 60)  + $oEndingDate->format('i'));
        
        // Run Query
        
        return $oDatabase->fetchAssoc("
            SELECT 
            (
                select min(d.open_minute)
                from $sTimeslotDayTable d
                where d.timeslot_id = t.timeslot_id
                and d.open_minute <= :iOpenSlot and d.close_minute > :iOpenSlot
            ) as opening_slot, 
            (
                select max(d.close_minute)
                from $sTimeslotDayTable d
                where d.timeslot_id = t.timeslot_id
                and d.close_minute >= :iCloseSlot and d.open_minute < :iCloseSlot
            ) as closing_slot
            FROM $sUserScheduleTable t
            WHERE schedule_id = :iScheduleId
            ",
            [
                ':iScheduleId' => $iScheduleId, 
                ':iOpenSlot' => $iOpenSlot,
                ':iCloseSlot' => $iCloseSlot, 
            ],
            [
                Type::INTEGER, 
                Type::INTEGER, 
                Type::INTEGER
            ]
        );
    }
    
    
    //---------------------------------------------
    # Hooks
    
    abstract protected function doGetRuleTypeId();
    
    abstract protected function doGetRepeatDayofWeek();
    
    abstract protected function doGetRepeatDayofMonth();
    
    abstract protected function doGetRepeatMonth();
    
    abstract protected function doGetRepeatWeekofYear();
    
    abstract protected function doGetSingleDayRule();
  
    abstract protected function doGetDefaultRuleName();
  
    abstract protected function doGetDefaultRuleDesc();
  
  
  
    //---------------------------------------------
    # Common Interface
 
    public function getRuleName()
    {
        if(empty($this->sRuleName)) {
            $this->sRuleName = $this->doGetDefaultRuleName();    
        }
        
        return $this->sRuleName;
        
    }
    
    public function getRuleDescription()
    {
        if(empty($this->sRuleDesc)) {
            $this->sRuleDesc = $this->doGetDefaultRuleDesc();
        }
        
        return $this->sRuleDesc;
    }
 
    
    public function getNewRuleCommand()
    {
        
        $oStartFromDate      = $this->oStartFromDate;
        $oEndtAtDate         = $this->oEndtAtDate;
        $iRuleTypeDatabaseId = $this->doGetRuleTypeId();
        $iTimeslotDatabaseId = $this->oSchedule->getTimeslotId();
        
        
        // Lookup the assigned time and match to a set of slots
        // in the assigned schedule
        $aSlots   = $this->matchSlotsToDate();
        
        if(!isset($aSlots['opening_slot']) || !isset($aSlots['closing_slot'])) {
            throw new \RuntimeException('Unable to match slots to a schedule from start or ending date supplied');
        }
        
        $iOpeningSlotInDay  = $aSlots['opening_slot'];
        $iClosingSlotInDay  = $aSlots['closing_slot'];
        
        $sRepeatDayofweek   = $this->doGetRepeatDayofWeek();
        $sRepeatDayofmonth  = $this->doGetRepeatDayofMonth();
        $sRepeatMonth       = $this->doGetRepeatMonth();
        $sRepeatWeekofyear  = $this->doGetRepeatWeekofYear();
        $bIsSingleDay       = $this->doGetSingleDayRule(); 
        $sRuleName          = $this->getRuleName();
        $sRuleDescription   = $this->getRuleDescription();
        
        return new CreateRuleCommand(
            $oStartFromDate
          , $oEndtAtDate
          , $iRuleTypeDatabaseId
          , $iTimeslotDatabaseId
          , $iOpeningSlotInDay
          , $iClosingSlotInDay
          , $sRepeatDayofweek
          , $sRepeatDayofmonth
          , $sRepeatMonth
          , $sRepeatWeekofyear
          , $bIsSingleDay
          , $sRuleName  
          , $sRuleDescription
        );    
        
    }
    
}
/* End of Class */
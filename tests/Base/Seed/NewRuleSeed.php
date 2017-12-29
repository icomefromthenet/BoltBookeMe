<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use DateTime;
use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class NewRuleSeed extends BaseSeed
{
    
   
   protected $aNewRules;
    
    
    
    protected function createRule(
        $iRuleTypeId, 
        $sRepeatMinute, 
        $sRepeatHour, 
        $sRepeatDayOfWeek,
        $sRepeatDayOfMonth,
        $sRepeatMonth,
        $sRepeatWeekOfYear,
        DateTime $oStartFrom,
        DateTime $oEndAt,
        $iTimeSlotId,
        $iOpeningSlot,
        $iCloseSlot,
        $iCalYear,
        $bIsSingleDay,
        $sRuleName,
        $sRuleDescription
        )
    {
        $oDatabase         = $this->getDatabase();
        $aTableNames       = $this->getTableNames();
        $iRuleId           = null;
        $sRuleTableName     = $aTableNames['bm_rule'];
        
            
        $aBind = [
            ':iRuleTypeId'      => $iRuleTypeId,
            ':repeatMinute'     => $sRepeatMinute,
            ':repeatHour'       => $sRepeatHour,
            ':repeatDayOfWeek'  => $sRepeatDayOfWeek,
            ':repeatDayOfMonth' => $sRepeatDayOfMonth,
            ':repeatMonth'      => $sRepeatMonth,
            'repeatWeekOfYear'  => $sRepeatWeekOfYear,
            ':oStartFrom'       => $oStartFrom,
            ':oEndAt'           => $oEndAt,
            ':iTimeslotId'      => $iTimeSlotId,
            ':iOpenSlot'        => $iOpeningSlot, 
            ':iCloseSlot'       => $iCloseSlot,
            ':iCalYear'         => $iCalYear,
            ':bIsSingleDay'     => $bIsSingleDay,
            ':sRuleName'        => $sRuleName, 
            ':sRuleDesc'        => $sRuleDescription,
        ];
      
        
        $aType = [
          ':iRuleTypeId'        => TYPE::INTEGER,
          ':repeatMinute'       => TYPE::STRING,
          ':repeatHour'         => TYPE::STRING,
          ':repeatDayOfWeek'    => TYPE::STRING,
          ':repeatDayOfMonth'   => TYPE::STRING,
          ':repeatMonth'        => TYPE::STRING,
          ':repeatWeekOfYear'   => TYPE::STRING,
          ':oStartFrom'         => TYPE::DATE,
          ':oEndAt'             => TYPE::DATE,
          ':iTimeslotId'        => TYPE::INTEGER,
          ':iOpenSlot'          => TYPE::INTEGER,
          ':iCloseSlot'         => TYPE::INTEGER,
          ':iCalYear'           => TYPE::INTEGER,
          ':bIsSingleDay'       => TYPE::BOOLEAN,
          ':sRuleName'          => TYPE::STRING,
          ':sRuleDesc'          => TYPE::STRING,
        ];
        
        $sSql  =" INSERT INTO $sRuleTableName (`rule_id`, `rule_type_id`, `repeat_minute`, `repeat_hour`, `repeat_dayofweek`, `repeat_dayofmonth`, `repeat_month`, `repeat_weekofyear` , `start_from`, `end_at`, `timeslot_id`, `open_slot`, `close_slot`, `cal_year`, `is_single_day`, `rule_name`,`rule_desc`) ";
        $sSql .=" VALUES (null, :iRuleTypeId, :repeatMinute, :repeatHour, :repeatDayOfWeek, :repeatDayOfMonth, :repeatMonth, :repeatWeekOfYear , :oStartFrom, :oEndAt, :iTimeslotId, :iOpenSlot, :iCloseSlot, :iCalYear, :bIsSingleDay, :sRuleName, :sRuleDesc )";   
        
	    $oDatabase->executeUpdate($sSql, $aBind, $aType);
        
        $iRuleId = $oDatabase->lastInsertId();
        
        if(empty($iRuleId)) {
            throw new \RuntimeException('Unable to create new Rule');
        }
        
        return $iRuleId;
             
    }
   
    
    
    protected function doExecuteSeed()
    {
        $aNewRules = [];
        
        foreach($this->aNewRules as $sKey => $aNewRule) {
            $aNewRules[$sKey] = $this->createRule(
                $aNewRule['RULE_TYPE_ID'],
                $aNewRule['REPEAT_MINUTE'],
                $aNewRule['REPEAT_HOUR'],
                $aNewRule['REPEAT_DAYOFWEEK'],
                $aNewRule['REPEAT_DAYOFMONTH'],
                $aNewRule['REPEAT_MONTH'],
                $aNewRule['REPEAT_WEEKOFYEAR'],
                $aNewRule['START_FROM'],
                $aNewRule['END_AT'],
                $aNewRule['TIMESLOT_ID'],
                $aNewRule['OPEN_SLOT'],
                $aNewRule['CLOSE_SLOT'],
                $aNewRule['CAL_YEAR'],
                $aNewRule['IS_SINGLE_DAY'],
                $aNewRule['RULE_NAME'],
                $aNewRule['RULE_DESC']
            );
            
        }
        
        return $aNewRules;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aNewRules)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aNewRules = $aNewRules;
   
    }
    
    
}
/* End of Class */

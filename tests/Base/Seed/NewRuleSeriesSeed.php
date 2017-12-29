<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use DateTime;
use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class NewRuleSeriesSeed extends BaseSeed
{
    
   
   protected $aNewRules;
    
    
    /**
     * Create the tmp table that will hold the slot series
     * 
     * @return void 
     */ 
    protected function createTempTable()
    {
        $oDatabase       = $this->getDatabase();
        $aTableNames     = $this->getTableNames();
        $sSeriesTmpTable = $aTableNames['bm_tmp_rule_series'];
        
        
        $oDatabase->query(
            "CREATE TEMPORARY TABLE IF NOT EXISTS $sSeriesTmpTable (
    		      `rule_id`           INT NOT NULL COMMENT 'FK to rule table',
    		      `rule_type_id`           INT NOT NULL COMMENT 'FK to rule type table',
    		      `timeslot_id`       INT NOT NULL COMMENT 'FK to slot table',
                  `y`                 SMALLINT NULL COMMENT 'year where date occurs',
                  `m`                 TINYINT NULL COMMENT 'month of the year',
                  `d`                 TINYINT NULL COMMENT 'numeric date part',
                  `dw`                TINYINT NULL COMMENT 'day number of the date in a week',
                  `w`                 TINYINT NULL COMMENT 'week number in the year',
                  `open_minute`       INT NOT NULL COMMENT 'Closing Minute component',    
                  `close_minute`      INT NOT NULL COMMENT 'Closing Minute component', 
                 
                  `closing_slot`      DATETIME NOT NULL COMMENT 'The closing slot time',
                  `opening_slot`      DATETIME NOT NULL COMMENT 'The opening slot time',
        
                  PRIMARY KEY ( `rule_id`,`closing_slot`)
    		    
    	    ) 
    	    ENGINE=MEMORY");
    	    
    	
    }
    
    
    /**
     * Clear the tmp series table for another run
     * 
     * @return void
     */ 
    protected function flushTempTable()
    {
        $oDatabase   = $this->getDatabase();
        $aTableNames = $this->getTableNames();
        
        $sSeriesTmpTable = $aTableNames['bm_tmp_rule_series'];
        
        $oDatabase->query("DROP TEMPORARY TABLE IF EXISTS $sSeriesTmpTable ");
        
    }
    
    
    
    protected function createRuleSeries(
       $iRuleId,
       $iRuleTypeId,
       $iTimeslotDatabaseId,
       DateTime $oStartFrom,
       DateTime $oEndAt,
       $iOpeningDaySlot,
       $iClosingDaySlot,
       $iCalYear,
       $bIsSingleDay,
       
       $aMonthRanges = null,
       $aDayMonthRanges = null,
       $aDayWeekRanges = null,
       $aWeekYearRanges = null
       
        )
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        $iRowsAffected      = 0;
        $sSeriesTmpTable    =  $aTableNames['bm_tmp_rule_series'];
        $sYearSlotTabale    =  $aTableNames['bm_timeslot_year'];
        
        
        $aSql            = [];
        $sSql            = '';
        $aBinds          = [
            ':iTimeSlotId'    => $iTimeslotDatabaseId,
            ':sOpeningSlot'   => $oStartFrom->format('dmY'),
            ':sClosingSlot'   => $oEndAt->format('dmY'),
            ':iCalYear'       => $iCalYear,
            ':iOpenMinute'    => $iOpeningDaySlot,
            ':iCloseMinute'   => $iClosingDaySlot,
            ':iRuleId'        => $iRuleId,
            ':iRuleTypeId'    => $iRuleTypeId,
        ];
        
        
        $aTypes = [
            ':iTimeSlotId'    => TYPE::getType(TYPE::INTEGER),
            ':sOpeningSlot'   => TYPE::getType(TYPE::STRING),
            ':sClosingSlot'   => TYPE::getType(TYPE::STRING),
            ':iCalYear'       => TYPE::getType(TYPE::INTEGER),
            ':iOpenMinute'    => TYPE::getType(TYPE::INTEGER),
            ':iCloseMinute'   => TYPE::getType(TYPE::INTEGER),
            ':iRuleId'        => TYPE::getType(TYPE::INTEGER),
            ':iRuleTypeId'    => TYPE::getType(TYPE::INTEGER),
        ];

       
     
        // Find all slots between applicability date and in the calender year
        // This will find slots that finish after the current calendar day. (Tail end)
        // E.g we have a slot that is say 11:59pm-12:01am It carries across two calendar days. 
        // `d`.`opening_slot` is a date check not a datetime, so need different query to fetch overlaps
        
        $a1Sql[] =" INSERT INTO $sSeriesTmpTable (`rule_id`,`rule_type_id`,`timeslot_id`,`y`,`m`,`d`,`dw`,`w`,`open_minute`,`close_minute`,`closing_slot`,`opening_slot`) ";
        $a1Sql[] =" SELECT :iRuleId, :iRuleTypeId, `d`.`timeslot_id`, `d`.`y`, `d`.`m`, `d`.`d`, `d`.`dw`, `d`.`w` , `d`.`open_minute`, `d`.`close_minute`,`d`.`closing_slot`, `d`.`opening_slot`";
        $a1Sql[] =" FROM $sYearSlotTabale d ";
        $a1Sql[] =" WHERE  `d`.`timeslot_id` = :iTimeSlotId ";
        $a1Sql[] =" AND date(`d`.`opening_slot`) < DATE_ADD(STR_TO_DATE(:sClosingSlot,'%d%m%Y'), INTERVAL 1 DAY) ";
        $a1Sql[] =" AND date(`d`.`closing_slot`) > STR_TO_DATE(:sOpeningSlot,'%d%m%Y') ";
        $a1Sql[] =" AND `d`.`y` = :iCalYear ";
        $a1Sql[] =" AND `d`.`open_minute` < :iCloseMinute ";
        $a1Sql[] =" AND `d`.`close_minute` >  :iOpenMinute";

        
     
        // This find all the slots between start and finish but excude overlaps ie slots that cover two calendar days.
        
        $a2Sql[] =" REPLACE INTO $sSeriesTmpTable (`rule_id`, `rule_type_id`,`timeslot_id`,`y`,`m`,`d`,`dw`,`w`,`open_minute`,`close_minute`,`closing_slot`,`opening_slot`) ";
        $a2Sql[] =" SELECT :iRuleId, :iRuleTypeId, `d`.`timeslot_id`, `d`.`y`, `d`.`m`, `d`.`d`, `d`.`dw`, `d`.`w` , `d`.`open_minute`, `d`.`close_minute`,`d`.`closing_slot`, `d`.`opening_slot`";
        $a2Sql[] =" FROM $sYearSlotTabale d ";
        $a2Sql[] =" WHERE  `d`.`timeslot_id` = :iTimeSlotId ";
        $a2Sql[] =" AND date(`d`.`opening_slot`) >= STR_TO_DATE(:sOpeningSlot,'%d%m%Y') ";
        $a2Sql[] =" AND date(`d`.`closing_slot`) <= STR_TO_DATE(:sClosingSlot,'%d%m%Y') ";
        $a2Sql[] =" AND `d`.`y` = :iCalYear ";
        $a2Sql[] =" AND `d`.`open_minute` >= :iOpenMinute ";
        $a2Sql[] =" AND `d`.`close_minute` <= :iCloseMinute ";


        // Limit of Months
        if(false === $bIsSingleDay) {
        
           $aSql[] = (is_array($aMonthRanges)) ? " AND ( `d`.`m` IN (".implode(',',$aMonthRanges['RANGE']).") AND `d`.`m` % ".$aMonthRanges['MOD'].' = 0)' : '';
           $aSql[] = (is_array($aDayMonthRanges)) ?" AND ( `d`.`d` IN (".implode(',',$aDayMonthRanges['RANGE']).") AND `d`.`d` % ".$aDayMonthRanges['MOD'].' = 0)' : '';
           $aSql[] = (is_array($aDayWeekRanges)) ?" AND (`d`.`dw` IN (".implode(',',$aDayWeekRanges['RANGE']).") AND (`d`.`dw`) % ".$aDayWeekRanges['MOD'].' = 0)' : '';
           $aSql[] = (is_array($aWeekYearRanges)) ? "  AND ( `d`.`w` IN (".implode(',',$aWeekYearRanges['RANGE']).") AND `d`.`w` % ".$aWeekYearRanges['MOD'].' = 0)' : '';
        }
        
        
           
        $sSql1  =  implode(PHP_EOL,$a1Sql);
        $sSql1 .=  implode(PHP_EOL,$aSql);
        
        //var_export($sSql1);
        //exit;

        $iRows1Affected = $oDatabase->executeUpdate($sSql1,$aBinds,$aTypes);
        
        $sSql2  =   implode(PHP_EOL,$a2Sql);
        $sSql2 .=  implode(PHP_EOL,$aSql);

        //var_export($sSql2);
        //exit;

        $iRows2Affected = $oDatabase->executeUpdate($sSql2,$aBinds,$aTypes);
        
        
        $iRowsAffected = $iRows1Affected + $iRows2Affected;
        
        if($iRowsAffected == 0) {
            throw new RuntimeException('Unable to build rule series for rule at id::'.$iRuleId);
        }
         
        return $iRowsAffected;
             
    }
   
    
    
    protected function doExecuteSeed()
    {
        
        $oDatabase      = $this->getDatabase();
        $aTableNames    = $this->getTableNames();
        $sSeriesTable   = $aTableNames['bm_rule_series'];
        $sRuleTmpTable  = $aTableNames['bm_tmp_rule_series'];
        
        $this->createTempTable();
        
        // Build Series
        
        foreach($this->aNewRules as $sKey => $aNewRule) {
            $this->createRuleSeries(
                $aNewRule['RULE_ID'],
                $aNewRule['RULE_TYPE_ID'],
                $aNewRule['TIMESLOT_ID'],
                $aNewRule['START_FROM'],
                $aNewRule['END_AT'],
                $aNewRule['OPEN_SLOT'],
                $aNewRule['CLOSE_SLOT'],
                $aNewRule['CAL_YEAR'],
                $aNewRule['IS_SINGLE_DAY'],
                
                $aNewRule['MonthRanges'],
                $aNewRule['DayMonthRanges'],
                $aNewRule['DayWeekRanges'] ,
                $aNewRule['WeekYearRanges']
            );
            
        }
        
        // Execute Bulk Series Insert
        
        $aSql[] = " INSERT INTO $sSeriesTable (`rule_id`, `rule_type_id`, `cal_year`, `slot_open`,`slot_close`) ";
        $aSql[] = " SELECT rule_id, rule_type_id, y, opening_slot, closing_slot ";
        $aSql[] = " FROM $sRuleTmpTable ";
        $aBinds = [];
            
        $sSql = implode(PHP_EOL, $aSql);
            
        $iRowsAffected = $oDatabase->executeUpdate($sSql,$aBinds,[]);
            
        if($iRowsAffected == 0) {
             throw new RuntimeException('Unable to build bulk rule series');
        }
             
        $this->flushTempTable();
        
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aNewRules)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aNewRules = $aNewRules;
   
    }
    
    
    
}
/* End of Class */

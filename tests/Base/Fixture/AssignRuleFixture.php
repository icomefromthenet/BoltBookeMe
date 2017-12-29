<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use RuntimeException;
use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewRuleSeed;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewRuleSeriesSeed;

class AssignRuleFixture extends BaseFixture
{
   
   
   
    
    public function runFixture(array $aAppConfig, DateTime $oNow)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
      
        $sRuleScheduleTable = $aTableNames['bm_rule_schedule'];
        $sScheduleTable     = $aTableNames['bm_schedule'];
        $sRuleTable         = $aTableNames['bm_rule']; 
      
        // Step1 , Asign Rules to each schedule using a bulk insert.
        $sSql = "
            insert into $sRuleScheduleTable (`rule_id`, `schedule_id`, `is_rollover`)
            SELECT `r`.`rule_id`, `s`.`schedule_id`, IF(`r`.`is_single_day` = 0,0,1) 
            FROM $sScheduleTable s, $sRuleTable r
        ";
        
        $iAffectedRows = $oDatabase->executeUpdate($sSql, [], []);
	        
        if($iAffectedRows == 0) {
            throw new RuntimeException('Unable to assign new schedule rules to any of the schedules');
        }
        
        $sRuleScheduleTable = $aTableNames['bm_rule_schedule'];
        $sRuleTable         = $aTableNames['bm_rule'];
        $sRuleTypeTable     = $aTableNames['bm_rule_type'];
        $sRuleSeriesTable   = $aTableNames['bm_rule_series'];
        $sScheduleTable     = $aTableNames['bm_schedule'];
        $sScheduleSlotTable = $aTableNames['bm_schedule_slot'];
        
        // Step2, Set schedules to default state.
        $aSql   = [];
        $sSql   = '';
        
        $aSql[] = " UPDATE  $sScheduleSlotTable ";
	    $aSql[] = " SET `is_available` = false, `is_excluded` = false, `is_override` = false ";
	   
	    $sSql = implode(PHP_EOL,$aSql);
	
	    $oDatabase->executeUpdate($sSql, [], []);
       
        // Step3, Refresh the schedules with rules.
        $aSql               = [];
        $sSql               = '';
         
        $aSql[] = " UPDATE $sScheduleSlotTable sl ";
        $aSql[] = " INNER JOIN ( ";
        $aSql[] = "     SELECT `s`.`schedule_id`, `rss`.`slot_open`, `rss`.`slot_close`, "; 
        $aSql[] = "             sum(IF(`rt`.`is_work_day` = true,1,0)) as is_available,  ";
        $aSql[] = "             sum(IF(`rt`.`is_exclusion` = true,1,0)) as is_excluded, ";
        $aSql[] = "             sum(IF(`rt`.`is_inc_override` = true,1,0)) as is_override ";
        $aSql[] = "     FROM $sRuleScheduleTable rs ";
        $aSql[] = "     JOIN $sRuleTable r on `r`.`rule_id` = `rs`.`rule_id` ";
        $aSql[] = "     JOIN $sScheduleTable s on `s`.`schedule_id` = `rs`.`schedule_id` AND `r`.`timeslot_id` = `s`.`timeslot_id` "; 
        $aSql[] = "     JOIN $sRuleTypeTable rt on `rt`.`rule_type_id` = `r`.`rule_type_id` ";
        $aSql[] = "     JOIN $sRuleSeriesTable rss on `rss`.`rule_type_id` = `r`.`rule_type_id` AND `rss`.`rule_id` = `rs`.`rule_id` ";
        $aSql[] = "     GROUP BY `s`.`schedule_id`,`rss`.`slot_open`, `rss`.`slot_close` ) crs";
        $aSql[] = "        ON  `sl`.`schedule_id` = `crs`.`schedule_id` AND `crs`.`slot_close` = `sl`.`slot_close` ";
        $aSql[] = " SET `sl`.`is_available` = IF(`crs`.`is_available` > 0,true,false), "; 
        $aSql[] = "     `sl`.`is_excluded` = IF(`crs`.`is_excluded` > 0,true,false), ";
        $aSql[] = "     `sl`.`is_override` = IF(`crs`.`is_override` > 0,true,false) ";
        

        $sSql = implode(PHP_EOL,$aSql);

        $iAffectedRows = $oDatabase->executeUpdate($sSql, [], []);
      
        if($iAffectedRows == 0) {
            throw new RuntimeException('Unable to regresh any schedules with new rule values');
        }
        
        
    }
    
   
    
}
/* End of Class */

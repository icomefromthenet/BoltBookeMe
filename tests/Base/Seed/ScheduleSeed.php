<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class ScheduleSeed extends BaseSeed
{
    
   
   protected $aNewSchedules;
    
    
    
    protected function addNewSchedule($iCalendarYear, $iMemberId, $iTimeSlotId)
    {
        $oDatabase              = $this->getDatabase();
        $aTableNames            = $this->getTableNames();
        $sScheduleTableName     = $aTableNames['bm_schedule'];
        $iScheduleId            = null;
        $aSql                   = [];
        
        # Step 1 Create The schedule
        
        $aSql[] = " INSERT INTO $sScheduleTableName (`schedule_id`,`timeslot_id`,`membership_id`,`calendar_year`,`registered_date`) ";
        $aSql[] = " VALUES (NULL, ?, ?, ?, NOW()) ";
        
        $sSql = implode(PHP_EOL,$aSql);
        
        $oIntType  = Type::getType(Type::INTEGER);
    
        $oDatabase->executeUpdate($sSql, [$iTimeSlotId,$iMemberId,$iCalendarYear], [$oIntType,$oIntType,$oIntType]);
        
        $iScheduleId = $oDatabase->lastInsertId();
        
        if(true == empty($iScheduleId)) {
            throw new DBALException('Could not Insert a new schedule');
        }
           
        
        return $iScheduleId;
             
    }
   
    
    protected function buildScheduleSlots()
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        
        $sScheduleSlotTableName = $aTableNames['bm_schedule_slot'];
        $sTimeSlotYearTableName = $aTableNames['bm_timeslot_year'];
        $sScheduleTableName     = $aTableNames['bm_schedule'];
        
        $a2Sql[] = " INSERT INTO $sScheduleSlotTableName (`schedule_id`, `slot_open`, `slot_close`)  ";
        $a2Sql[] = " SELECT  `s`.`schedule_id` , `c`.`opening_slot` as slot_open , `c`.`closing_slot` as slot_close ";
        $a2Sql[] = " FROM $sTimeSlotYearTableName c, $sScheduleTableName s";
        $a2Sql[] = " WHERE `c`.`y` = `s`.`calendar_year` ";
        $a2Sql[] = " AND `c`.`timeslot_id` = `s`.`timeslot_id` ";
        
        $s2Sql = implode(PHP_EOL,$a2Sql);
        
        $iRowsAffected = $oDatabase->executeUpdate($s2Sql, [], []);
        
        if($iRowsAffected == 0) {
	        throw new \RuntimeException('Could not generate schedule slots');
	    }
	    
    }
  
    
    
    protected function doExecuteSeed()
    {
       
        $aScheduleIds = [];
        
        foreach($this->aNewSchedules as $sKey => $aSchedule) {
            $aScheduleIds[$sKey] = $this->addNewSchedule(
                $aSchedule['CALENDAR_YEAR'],
                $aSchedule['MEMBERSHIP_ID'],
                $aSchedule['TIMESLOT_ID']
            );
        }
        
        
        // Bulk build slots
        
        $this->buildScheduleSlots();
        
        return $aScheduleIds;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aNewSchedules)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aNewSchedules = $aNewSchedules;
   
    }
    
    
    
    
}
/* End of Class */

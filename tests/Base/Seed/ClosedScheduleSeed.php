<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class ClosedScheduleSeed extends BaseSeed
{
    
   
   protected $aClosedSchedules;
    
    
    
    protected function closeSchedule($iScheduleId, $oCloseDate)
    {
        $oDatabase             = $this->oDatabaseAdapter;
        $aTableNames            = $this->getTableNames();
        
        $sScheduleTableName     = $aTableNames['bm_schedule'];
        $sScheduleSlotTableName = $aTableNames['bm_schedule_slot'];
        
        
        $aSql                   = [];
        $a2Sql                  = [];
        $a3Sql                  = [];
       
        
        # Step 1 Set the close date and the carryover
        
        $aSql[] = " UPDATE $sScheduleTableName  ";
        $aSql[] = " SET  is_carryover = false, close_date = ? ";
        $aSql[] = " WHERE schedule_id = ? ";
        
        $sSql = implode(PHP_EOL,$aSql);
        
        # Step 2 Blackout the slots from the close date but first obtain a row lock to stop bookings
        
        $a2Sql[] = " SELECT `slot_open` ";
        $a2Sql[] = " FROM $sScheduleSlotTableName  ";
        $a2Sql[] = " WHERE schedule_id = ? ";
        $a2Sql[] = " AND slot_open >= ? ";
        $a2Sql[] = " FOR UPDATE ";
        
        $s2Sql = implode(PHP_EOL,$a2Sql);
        
        $a3Sql[] = " UPDATE $sScheduleSlotTableName  ";
        $a3Sql[] = " SET  is_closed = true ";
        $a3Sql[] = " WHERE schedule_id = ? ";
        $a3Sql[] = " AND slot_open >= ?";
          
        $s3Sql = implode(PHP_EOL,$a3Sql);
          
        $oDateType = Type::getType(Type::DATE);
        $oIntType  = Type::getType(Type::INTEGER);
    
        # Close the schedule
        $iRowsAffected = $oDatabase->executeUpdate($sSql, [$oCloseDate,$iScheduleId], [$oDateType,$oIntType]);
        
        if($iRowsAffected == 0) {
            throw new RuntimeException('Could not match a schedule to close please check database id');
        }
        
        // Execute the lock Statement
        $oDatabase->executeUpdate($s2Sql, [$iScheduleId, $oCloseDate], [$oIntType, $oDateType]);
        
        // Execute the Blockout update
        $iRowsAffected = $oDatabase->executeUpdate($s3Sql, [$iScheduleId,$oCloseDate], [$oIntType,$oDateType]);
        
        if($iRowsAffected == 0) {
            throw new RuntimeException('Could not match a schedule to blackout dates please check database id');
        }
        
        
        return true;
             
    }
   
    
 
    
    
    protected function doExecuteSeed()
    {
       
        foreach($this->aClosedSchedules as $sKey => $aSchedule) {
            $this->closeSchedule(
                $aSchedule['SCHEDULE_ID'],
                $aSchedule['CLOSE_DATE']
            );
        }
        
        
        
        return true;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aClosedSchedules)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aClosedSchedules = $aClosedSchedules;
   
    }
    
    
}
/* End of Class */

<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class SlotSeed extends BaseSeed
{
    
    protected $iTimeslotLength;
    
    protected $bIsActiveSlot;
    
    
    
    protected function addNewSlot()
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        
        $sTimeSlotTableName = $aTableNames['bm_timeslot'];
        $iTimeSlotId        = null;
        $iSlotLength        = $this->iTimeslotLength;
        
        $sSql = " INSERT INTO $sTimeSlotTableName (timeslot_id, timeslot_length, is_active_slot) VALUES (null, ?, ?)";

	    
	    
    
        $oDatabase->executeUpdate(
            $sSql, 
            [$this->iTimeslotLength, $this->bIsActiveSlot],
            [Type::getType(Type::INTEGER),Type::getType(Type::BOOLEAN)]
        );
        
        $iTimeSlotId = $oDatabase->lastInsertId();
        
        return $iTimeSlotId;
             
    }
   
    protected function buildSlotDays($iTimeSlotId)
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        
        $sTimeSlotTableName     = $aTableNames['bm_timeslot'];
        $sTimeSlotDayTableName  = $aTableNames['bm_timeslot_day'];
        $sIntsTableName         = $aTableNames['bm_ints']; 
 
        $aSql                   = [];
        
        
        $aSql[] = " INSERT INTO $sTimeSlotDayTableName (`timeslot_day_id`,`timeslot_id`,`open_minute`,`close_minute`) ";
        $aSql[] = " SELECT null, :iTimeSlotId, 0 as start,  `t`.`timeslot_length` as end ";
        $aSql[] = " FROM $sTimeSlotTableName t  ";
        $aSql[] = " WHERE `t`.`timeslot_id` = :iTimeSlotId ";                                                                                                                                  
        $aSql[] = " UNION ";
        $aSql[] = " SELECT null, :iTimeSlotId, `ints`.`tick` as start, `t`.`timeslot_length`+`ints`.`tick` as end  ";                                                                                      
        $aSql[] = " FROM ( ";                                                                                                                                                                             
        $aSql[] = "     SELECT 1 + (`a`.`i`*1000 + `b`.`i`*100 + `c`.`i`*10 + `d`.`i`) as tick   ";                                                                                                        
        $aSql[] = "     FROM $sIntsTableName a JOIN $sIntsTableName b JOIN $sIntsTableName c JOIN $sIntsTableName d ";                                                                                                  
        $aSql[] = "     WHERE (`a`.`i`*1000 + `b`.`i`*100 + `c`.`i`*10 + `d`.`i`) <= (60*24) "; 
        $aSql[] = "     ORDER BY 1";
        $aSql[] = " ) ints ";                                                                                                                                                                              
        $aSql[] = " CROSS JOIN $sTimeSlotTableName t on `t`.`timeslot_id` = :iTimeSlotId   ";                                                                                                                                
        $aSql[] = " WHERE mod(`ints`.`tick`, `t`.`timeslot_length`) = 0 ";                                                                                                            
        $aSql[] = " AND (`t`.`timeslot_length`+`ints`.`tick`) <=  (60*24) ";                                                                                                            
        $aSql[] = " ORDER BY start ";
            
             
        
        $sSql = implode(PHP_EOL,$aSql);
        
        $oIntType = Type::getType(Type::INTEGER);
    
        $oDatabase->executeUpdate($sSql, [':iTimeSlotId' => $iTimeSlotId], [$oIntType]);
              
    }
    
    
    protected function buildSlotYears($iTimeSlotId)
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        
        $sTimeSlotTableName     = $aTableNames['bm_timeslot'];
        $sTimeSlotDayTableName  = $aTableNames['bm_timeslot_day'];
        $sTimeslotYearTableName = $aTableNames['bm_timeslot_year'];
        $sCalenderTableName     = $aTableNames['bm_calendar'];
     
        $aSql                   = [];
        
        
        $aSql[] = " INSERT INTO $sTimeslotYearTableName (`timeslot_year_id`, `timeslot_id`, `opening_slot`, `closing_slot`, `y`, `m`, `d`, `dw`, `w`, `open_minute`, `close_minute`)  ";
        $aSql[] = " SELECT NULL, ?, (`c`.`calendar_date` + INTERVAL `s`.`open_minute` MINUTE) , (`c`.`calendar_date` + INTERVAL `s`.`close_minute` MINUTE), ";
        $aSql[] = " `c`.`y`, `c`.`m`, `c`.`d`, `c`.`dw`, `c`.`w`, `s`.`open_minute`, `s`.`close_minute` ";
        $aSql[] = " FROM $sCalenderTableName c ";
        $aSql[] = " CROSS JOIN $sTimeSlotDayTableName s on `s`.`timeslot_id` = ?";
        
        $sSql = implode(PHP_EOL,$aSql);
    
        
        $oIntType = Type::getType(Type::INTEGER);
	    
	   $oDatabase->executeUpdate(
	       $sSql, 
	       [$iTimeSlotId,$iTimeSlotId], 
	       [$oIntType,$oIntType]
	   );
                 
	 
        
              
    }
    
  
    
    
    protected function doExecuteSeed()
    {
        $iTimeSlotId = $this->addNewSlot();        
        
        $this->buildSlotDays($iTimeSlotId);
        $this->buildSlotYears($iTimeSlotId);
        
        return $iTimeSlotId;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, $iTimeslotLength, $bIsActiveSlot)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
        $this->iTimeslotLength = $iTimeslotLength;
        $this->bIsActiveSlot   = $bIsActiveSlot;
   
    }
    
    
    
    
}
/* End of Class */

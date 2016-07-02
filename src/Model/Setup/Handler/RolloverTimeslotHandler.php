<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\RolloverTimeslotCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\SetupException;


/**
 * Used to build slot years when rolling over schedules
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RolloverTimeslotHandler 
{
    
    /**
     * @var array   a map internal table names to external names
     */ 
    protected $aTableNames;
    
    /**
     * @var Doctrine\DBAL\Connection    the database adpater
     */ 
    protected $oDatabaseAdapter;
    
    
    
    public function __construct(array $aTableNames, Connection $oDatabaseAdapter)
    {
        $this->oDatabaseAdapter = $oDatabaseAdapter;
        $this->aTableNames      = $aTableNames;
        
        
    }
    
    
    public function handle(RolloverTimeslotCommand $oCommand)
    {
        
        $oDatabase              = $this->oDatabaseAdapter;
        $sTimeSlotTableName     = $this->aTableNames['bm_timeslot'];
        $sTimeSlotDayTableName  = $this->aTableNames['bm_timeslot_day'];
        $sTimeslotYearTableName = $this->aTableNames['bm_timeslot_year'];
        $sCalenderTableName     = $this->aTableNames['bm_calendar'];
        $sCalenderYearTableName = $this->aTableNames['bm_calendar_years'];
        $aSql                   = [];
        $iTimeSlotId            = $oCommand->getTimeSlotId();
        
        
        $aFoundSql[] =" SELECT `cy`.`y` ";
        $aFoundSql[] =" FROM $sCalenderYearTableName cy ";
        $aFoundSql[] =" WHERE `cy`.`y` NOT IN (select distinct `td`.`y` FROM $sTimeslotYearTableName td) ";
        
        $sFoundSql = implode(PHP_EOL,$aFoundSql);
        
      
        
        
        $aSql[] = " INSERT INTO $sTimeslotYearTableName (`timeslot_year_id`, `timeslot_id`, `opening_slot`, `closing_slot`, `y`, `m`, `d`, `dw`, `w`, `open_minute`, `close_minute`)  ";
        $aSql[] = " SELECT NULL, ?, (`c`.`calendar_date` + INTERVAL `s`.`open_minute` MINUTE) , (`c`.`calendar_date` + INTERVAL `s`.`close_minute` MINUTE), ";
        $aSql[] = " `c`.`y`, `c`.`m`, `c`.`d`, `c`.`dw`, `c`.`w`, `s`.`open_minute`, `s`.`close_minute` ";
        $aSql[] = " FROM $sCalenderTableName c ";
        $aSql[] = " CROSS JOIN $sTimeSlotDayTableName s on `s`.`timeslot_id` = ?";
        $aSql[] = " WHERE `c`.`y` = ? ";
        
        $sSql = implode(PHP_EOL,$aSql);
     
        
        try {
	    
	        $oIntType       = Type::getType(Type::INTEGER);
	        $iAffectedTotal = 0;
	        
	        // fetch list of calendar years that need to have timeslots generated
	        $aYears = $oDatabase->fetchAll($sFoundSql, [], []);
	        
	        $sSql = implode(PHP_EOL,$aSql);
	        
	        // Build slots for these missing years
	        foreach($aYears as $aYear) {
	            $iAffected = $oDatabase->executeUpdate($sSql, [$iTimeSlotId,$iTimeSlotId,$aYear['y']], [$oIntType,$oIntType,$oIntType]);
	        
    	        if($iAffected == 0) {
    	            throw SetupException::hasFailedToRollover($oCommand, $e);
    	        }
    	        
    	        $iAffectedTotal += $iAffected;
	        }
	        
	        $oCommand->setRollOverNumber($iAffectedTotal);
	             
	    }
	    catch(DBALException $e) {
	        throw SetupException::hasFailedToRollover($oCommand, $e);
	    }
      
        
        return true;
    }
     
    
}
/* End of File */
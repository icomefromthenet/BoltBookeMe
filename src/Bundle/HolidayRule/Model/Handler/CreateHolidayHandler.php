<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model\Appointment\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Bundle\HolidayRule\Model\HolidayRuleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Bundle\HolidayRule\Model\Command\CreateHolidayCommand;



/**
 * Used to convert Holiday Definition into A Schedule Rule
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateHolidayHandler 
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
    
    
    public function handle(CreateHolidayCommand $oCommand)
    {
        $oDatabase       = $this->oDatabaseAdapter;
        $sTableName      = $this->aTableNames['bm_holiday'];
        
        $aSaveSql        = [];
        $sSaveSql        = '';
     
        
        $sSaveSq = " INSERT INTO $sTableName (holiday_hash, schedule_id, rule_id) VALUES (:sHash, :iScheduleId, :iRuleId)";
          
        
        try {
            
	        $oIntType    = Type::getType(Type::INTEGER);
	        $oStringType = Type::getType(Type::STRING); 
	        
	       $aParams =  [
	            'iRuleId'        => $oCommand->getRuleId(),
	            'iScheduleId'    => $oCommand->getScheduleId(),
	            'sHash'          => md5($oCommand->geHolidayDatetime()->format('d/m/Y') .'::'. $oCommand->geName())
	       ];
	       
	        
	       $aTypes = [
	            'iRuleId'      => $oIntType,
	            'iScheduleId'  => $oIntType,
	            'sHash'        => $oStringType
	       ];
	        
	       
	       	$iRowsAffected = $oDatabase->executeUpdate($sSaveSql, $aParams, $aTypes);
	        
	        if($iRowsAffected == 0) {
	            throw new DBALException('Could not save holiday to applied database');
	        }
	             
	    }
	    catch(DBALException $e) {
	        throw HolidayRuleException::hasFailedToSaveHoliday($oCommand,$e);
	    }
        
        
        return true;
    }
     
    
}
/* End of File */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\MoveApptWaitingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;


/**
 * Used to move appointment to waiting list
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class MoveApptWaitingHandler 
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
    
    
    public function handle(MoveApptWaitingCommand $oCommand)
    {
        $oDatabase               = $this->oDatabaseAdapter;
        $sApptTableName          = $this->aTableNames['bm_appointment'];
        
        $aSaveSql        = [];
        $sSaveSql        = '';
     
        
        $aSaveSql[] = " UPDATE $sApptTableName  ";
        $aSaveSql[] = " SET `status_code` = :sStatusCode,";
        $aSaveSql[] = "     `booking_id`  = NULL ";
        $aSaveSql[] = " WHERE `status_code` IN ('A','C') ";
        $aSaveSql[] = " AND `appointment_id`= :iApptId";
          
        $sSaveSql = implode(PHP_EOL,$aSaveSql);
      
        
        try {
            
	        $oIntType    = Type::getType(Type::INTEGER);
	        $oStringType = Type::getType(Type::STRING); 
	        
	        $aParams =  [
	            'iApptId'        => $oCommand->getAppointmentId(),
	            'sStatusCode'    => $oCommand->getStatusCode(),
	       ];
	       
	        
	       $aTypes = [
	            'iApptId'        => $oIntType,
	            'sStatusCode'    => $oStringType,
	       ];
	        
	       
	       	$iRowsAffected = $oDatabase->executeUpdate($sSaveSql, $aParams, $aTypes);
	        
	        if($iRowsAffected == 0) {
	            throw new DBALException('Could not move assined appointment to the waiting list');
	        }
	             
	    }
	    catch(DBALException $e) {
	        throw AppointmentException::hasFailedToMoveApptWaitingList($oCommand,$e);
	    }
        
        
        return true;
    }
     
    
}
/* End of File */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CompleteApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;


/**
 * Used to mark an appointment on as completed
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CompleteApptHandler 
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
    
    
    public function handle(CompleteApptCommand $oCommand)
    {
        $oDatabase               = $this->oDatabaseAdapter;
        $sApptTableName          = $this->aTableNames['bm_appointment'];
        
        $aSaveSql        = [];
        $sSaveSql        = '';
     
        
        $aSaveSql[] = " UPDATE $sApptTableName  ";
        $aSaveSql[] = " SET `status_code` = :sStatusCode";
        $aSaveSql[] = " WHERE `status_code` = 'A' ";
        $aSaveSql[] = " AND `booking_id`    IS NOT NULL ";
        $aSaveSql[] = " AND `appointment_id`= :iApptId ";
          
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
	            throw new DBALException('Could not complete appointment');
	        }
	             
	    }
	    catch(DBALException $e) {
	        throw AppointmentException::hasFailedToCancelAppt($oCommand,$e);
	    }
        
        
        return true;
    }
     
    
}
/* End of File */
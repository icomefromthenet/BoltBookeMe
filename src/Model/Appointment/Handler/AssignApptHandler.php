<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\AssignApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;


/**
 * Used to assign a booking to an appointment on the waiting list
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class AssignApptHandler 
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
    
    
    public function handle(AssignApptCommand $oCommand)
    {
        $oDatabase               = $this->oDatabaseAdapter;
        $sApptTableName          = $this->aTableNames['bm_appointment'];
        
        $aSaveSql        = [];
        $sSaveSql        = '';
     
        
        $aSaveSql[] = " UPDATE $sApptTableName  ";
        $aSaveSql[] = " SET `status_code` = :sStatusCode, `booking_id` = :iBookingId,";
        $aSaveSql[] = " `instructions` = IFNULL(:sInstructions,`instructions`) ";
        $aSaveSql[] = " WHERE `status_code` = 'W' ";
        $aSaveSql[] = " AND `booking_id`    IS NULL ";
        $aSaveSql[] = " AND `appointment_id`= :iApptId ";
          
        $sSaveSql = implode(PHP_EOL,$aSaveSql);
      
        
        try {
            
	        $oIntType    = Type::getType(Type::INTEGER);
	        $oStringType = Type::getType(Type::STRING); 
	        
	        $aParams =  [
	            'iApptId'        => $oCommand->getAppointmentId(),
	            'sStatusCode'    => $oCommand->getStatusCode(),
	            'iBookingId'     => $oCommand->getBookingId(),
	            'sInstructions'  => $oCommand->getInstructions(),
	       ];
	       
	        
	       $aTypes = [
	            'iApptId'        => $oIntType,
	            'sStatusCode'    => $oStringType,
	            'iBookingId'     => $oIntType,
	            'sInstructions'    => $oStringType,
	       ];
	        
	       
	       	$iRowsAffected = $oDatabase->executeUpdate($sSaveSql, $aParams, $aTypes);
	        
	        if($iRowsAffected == 0) {
	            throw new DBALException('Could not link booking to appointment');
	        }
	             
	    }
	    catch(DBALException $e) {
	        throw AppointmentException::hasFailedToAssignBookingToAppt($oCommand,$e);
	    }
        
        
        return true;
    }
     
    
}
/* End of File */
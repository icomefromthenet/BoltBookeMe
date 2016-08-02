<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;


/**
 * Used to create a new appointment at this stage NO booking would be assigned.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateApptHandler 
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
    
    
    public function handle(CreateApptCommand $oCommand)
    {
        $oDatabase               = $this->oDatabaseAdapter;
        $sApptTableName          = $this->aTableNames['bm_appointment'];
        
        $aSaveSql        = [];
        $sSaveSql        = '';
     
        
        $aSaveSql[] = " INSERT INTO  $sApptTableName (`appointment_id`, `customer_id` , `instructions`, `status_code` )";
        $aSaveSql[] = " VALUES (null, :iCustomerId, :sInstructions, :sStatusCode) ";
          
        $sSaveSql = implode(PHP_EOL,$aSaveSql);
      
        
        try {
            
	        $oIntType    = Type::getType(Type::INTEGER);
	        $oStringType = Type::getType(Type::STRING); 
	        
	        $aParams =  [
	            'iCustomerId'    => $oCommand->getCustomerId(),
	            'sInstructions'  => $oCommand->getInstructions(),
	            'sStatusCode'    => $oCommand->getStatusCode(),
	       ];
	       
	       
	        
	       $aTypes = [
	            'iCustomerId'    => $oIntType,
	            'sInstructions'  => $oStringType,
	            'sStatusCode'    => $oStringType,
	       ];
	        
	       
	       	$iRowsAffected = $oDatabase->executeUpdate($sSaveSql, $aParams, $aTypes);
	        
	        if($iRowsAffected == 0) {
	            throw new DBALException('Could not create appointment');
	        }
	        
	        $oCommand->iAppointmentId = $oDatabase->lastInsertId();
	             
	    }
	    catch(DBALException $e) {
	        throw AppointmentException::hasFailedToCompleteAppt($oCommand,$e);
	    }
        
        
        return true;
    }
     
    
}
/* End of File */
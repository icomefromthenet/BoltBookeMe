<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Decorator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentNumberGenerator;


/**
 * Used update the appointment with a human friendly number generated using
 * the database id as a seed, must be created after the database record is saved
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ApptNumDecorator
{
    
    /**
     * @var array   a map internal table names to external names
     */ 
    protected $aTableNames;
    
    /**
     * @var Doctrine\DBAL\Connection    the database adpater
     */ 
    protected $oDatabaseAdapter;
    
    
    protected $oHandler;
    
    /**
     * @var  Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentNumberGenerator   Number Generator
     */ 
    protected $oGenerator;
    
    
    public function __construct($oInternalHander ,array $aTableNames, Connection $oDatabaseAdapter, AppointmentNumberGenerator $oGenerator)
    {
        $this->oDatabaseAdapter = $oDatabaseAdapter;
        $this->aTableNames      = $aTableNames;
        $this->oHandler         = $oInternalHander;   
        $this->oGenerator       = $oGenerator;
        
    }
    
    
    public function handle(CreateApptCommand $oCommand)
    {
        $oDatabase         = $this->oDatabaseAdapter;
        $sAppTableName     = $this->aTableNames['bm_appointment'];
        
        // create the appointment 
        $this->oHandler->handle($oCommand);
        
        // fetch the seed 
        
        $iAppointmentSeed = $oCommand->getAppointmentId();
        
        if(true === empty($iAppointmentSeed)) {
            throw AppointmentException::hasFailedCreateApptNumberSeedEmpty($oCommand);
        }
       
        
        // call generator
        
        $sHumanApptNumber = $this->oGenerator->getApptNumber($iAppointmentSeed);
         
        // update the appointment
        
        $iRowsAffected = $oDatabase->executeUpdate("UPDATE $sAppTableName SET appointment_no = :sApptNumber WHERE appointment_id = :iAppId"
                                 ,['iAppId' => $iAppointmentSeed, 'sApptNumber' => $sHumanApptNumber]);
         
        if($iRowsAffected == 0) {
            throw AppointmentException::hasFailedCreateApptNumber($oCommand);
        }
        
        // Assign the number to the command
        
        $oCommand->sApptNumber = $sHumanApptNumber;
        
        
        
        return true;
        
    }
     
    
}
/* End of File */
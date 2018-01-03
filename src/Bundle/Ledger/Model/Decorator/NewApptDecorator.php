<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Model\Decorator;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use DBALGateway\Table\GatewayProxyCollection;
use Psr\Log\LoggerInterface;
use IComeFromTheNet\GeneralLedger\Entity\LedgerUser;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\LedgerBundleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Model\GuidTrait;


/**
 * Used to create a ledger user for this new appointment
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class NewApptDecorator
{
    
    use GuidTrait;
    
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
     * @var DBALGateway\Table\GatewayProxyCollection
     */ 
    protected $oGatewayProxy;
    
    /**
     * @var Psr\Log\LoggerInterface
     */ 
    protected $oLogger;
    
    /**
     * @var DateTime;
     */ 
    protected $oNow;
    
    
    public function __construct($oInternalHander , array $aTableNames, Connection $oDatabaseAdapter, GatewayProxyCollection $oGatewayProxy, LoggerInterface $oLogger, DateTime $oNow)
    {
        $this->oDatabaseAdapter = $oDatabaseAdapter;
        $this->aTableNames      = $aTableNames;
        $this->oHandler         = $oInternalHander;   
        $this->oGatewayProxy    = $oGatewayProxy;
        $this->oLogger          = $oLogger;
        $this->oNow             = $oNow;
        
    }
    
    
    public function handle(CreateApptCommand $oCommand)
    {
        $oDatabase         = $this->oDatabaseAdapter;
        $sAppTableName     = $this->aTableNames['bm_appointment'];
        $oGateway          = $this->oGatewayProxy->getGateway('ledger_user');
        
        // create the appointment 
        $this->oHandler->handle($oCommand);
        
        $sAppId = $oCommand->getAppointmentId();
        
        if(true === empty($sAppId)) {
            throw LedgerBundleException::appointmentNumberEmpty($oCommand);
        }
        
        // create a new ledger user
        $oEntity = new LedgerUser($oGateway,$this->oLogger);
        
        $oEntity->sExternalGUID  = $this->guid(false);
        $oEntity->oRegoDate      = new \DateTime($this->oNow);
        
        $oEntity->save();
        
        if(true === empty($oEntity->iUserID)) {
            $aError = $oEntity->getLastQueryResult();
           
            throw LedgerBundleException::unableToCreateLedgerUser($oCommand, null, $aError);
        }
        
         
        // update the appointment with the leder user
        
        $iRowsAffected = $oDatabase->executeUpdate("
            UPDATE $sAppTableName 
            SET ledger_user_id = :sLedgerUser 
            WHERE appointment_id = :iAppId"
            ,['iAppId' => $sAppId, 'sLedgerUser' => $oEntity->iUserID]
        );
         
        if($iRowsAffected == 0) {
            throw AppointmentException::unableToUpdateAppointmentWithLedgerUser($oCommand);
        }
        
        
        return true;
        
    }
    
}
/* End of File */
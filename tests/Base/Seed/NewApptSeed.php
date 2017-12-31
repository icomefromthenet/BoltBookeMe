<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;


class NewApptSeed extends BaseSeed
{
    
   
   protected $aAppointments;
    
    
    
    protected function createAppointment(
        $iAppointmentId,
        $iBookingId,
        $iCustomerId,
        $sApptNo,
        $iLedgerUserId
    )
    {
        $oDatabase         = $this->getDatabase();
        $aTableNames       = $this->getTableNames();
        
        $sApptTableName   = $aTableNames['bm_appointment'];
        
        $sSql  = " INSERT INTO $sApptTableName (`appointment_id`, `booking_id`, `status_code`, `customer_id`, `instructions`, `appointment_no`, `ledger_user_id`) 
                  VALUES (:iAppointmentId, :iBookingId, 'A', :iCustomerId, ':sInstructions', ':sApptNo', ':iLedgerUserId') ";
        
	    
        $oIntegerType = Type::getType(Type::INTEGER);
    
        $aParams = [
            ':iAppointmentId' => $iAppointmentId,
            ':iBookingId'     => $iBookingId,
            'iCustomerId'     => $iCustomerId,
            ':sApptNo'        => $sApptNo,
            ':iLedgerUserId' => $iLedgerUserId
        ];
        
        $aTypes = [
            ':iAppointmentId' => Type::INTEGER,
            ':iBookingId'     => Type::INTEGER,
            'iCustomerId'     => Type::INTEGER,
            ':sApptNo'        => Type::STRING,
            ':iLedgerUserId' => Type::INTEGER,
        ];
    
        $iAffected = $oDatabase->executeUpdate($sSql, $aParams, $aTypes);
        
        if(true === empty($iAffected)) {
            throw RuntimeException('Unable to assign meember to team');
        }
       
             
    }
   
    protected function createLedgerUsers($iUserId, $sGUID)
    {
        $oDatabase         = $this->getDatabase();
        $aTableNames       = $this->getTableNames();
        
        $sTeamMemberTableName   = $aTableNames['bm_schedule_team_members'];
        
        $sSql  = " INSERT INTO bolt_bm_ledger_user (`user_id`, `external_guid`, `rego_date`) VALUES (:iUserId, :sGUID, NOW()) ";
        
	    
        $oIntegerType = Type::getType(Type::INTEGER);
        $oStringType = Type::getType(Type::STRING);
    
        $aParams = [
            ':iUserId' => $iUserId,
            ':sGUID'   => $sGUID,  
        ];
    
        $iAffected = $oDatabase->executeUpdate($sSql, $aParams, [$oIntegerType, $oStringType]);
        
        if(true === empty($iAffected)) {
            throw RuntimeException('Unable to assign meember to team');
        }
        
    }
    
    
    protected function doExecuteSeed()
    {
        foreach($this->aAppointments as $sKey => $aAppt) {
            
            $this->createLedgerUsers(
                $aAppt['USER_ID'], 
                $aAppt['EXTERNAL_GUID']
            );
            
            $this->createAppointment(
                $aAppt['APPOINTMENT_ID'],
                $aAppt['BOOKING_ID'],
                $aAppt['CUSTOMER_ID'],
                $aAppt['APPT_NO'],
                $aAppt['USER_ID']
            );
            
            
        }
        
        return true;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aAppointments)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aAppointments = $aAppointments;
   
    }
    
    
}
/* End of Class */

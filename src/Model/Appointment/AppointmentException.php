<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment;

use League\Tactician\Exception\Exception as BusException;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\AssignApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CancelApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CompleteApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\MoveApptWaitingCommand;


/**
 * Custom Exception for Schedule Errors.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class AppointmentException extends BookMeException implements BusException
{
    /**
     * @var mixed
     */
    public $oCommand;
    
    
    /**
     * @param CreateApptCommand $oCommand
     * @param DBALException     $oDatabaseException 
     *
     * @return static
     */
    public static function hasFailedToCreateAppt(CreateApptCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'Unable to create new appointment for customer at id '.$oCommand->getCustomerId(), null, $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    /**
     * @param CreateApptCommand $oCommand
     * @param DBALException     $oDatabaseException 
     *
     * @return static
     */
    public static function hasFailedCreateApptNumberSeedEmpty(CreateApptCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'Unable to create appointment number as the seed is empty', null, $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    /**
     * @param CreateApptCommand $oCommand
     * @param DBALException     $oDatabaseException 
     *
     * @return static
     */
    public static function hasFailedCreateApptNumber(CreateApptCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'Unable to create appointment number appointment not found at id '.$oCommand->getCustomerId(), null, $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
   
    /**
     * @param CreateApptCommand $oCommand
     * @param DBALException     $oDatabaseException 
     *
     * @return static
     */
    public static function hasFailedToCancelAppt(CancelApptCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'Unable to create cancel appointment at id '.$oCommand->getAppointmentId(). ' the appointment is either not found or not in correct status ', null, $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
   
    /**
     * @param CreateApptCommand $oCommand
     * @param DBALException     $oDatabaseException 
     *
     * @return static
     */
    public static function hasFailedToAssignBookingToAppt(AssignApptCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'Unable to assign booking at id '.$oCommand->getBookingId().' to appointment at id '.$oCommand->getAppointmentId(). ' the appointment is either not found or not in correct status ', null, $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
   
    
    /**
     * Return the command that has failed validation
     * 
     * @return mixed
     */
    public function getCommand()
    {
        return $this->oCommand;
    }
    
    
}
/* End of File */
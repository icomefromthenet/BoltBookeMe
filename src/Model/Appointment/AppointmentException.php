<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment;

use League\Tactician\Exception\Exception as BusException;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\


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
     * @param TakeBookingCommand $oCommand
     *
     * @return static
     */
    public static function hasFailedToReserveSlots(TakeBookingCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'Unable to reserve schedule slots for schedule at id '.$oCommand->getScheduleId() 
            .' time from '.$oCommand->getOpeningSlot()->format('Y-m-d H:i:s') 
            .' until '.$oCommand->getClosingSlot()->format('Y-m-d H:i:s') , 0 , $oDatabaseException
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
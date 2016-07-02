<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\CalAddYearCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\SlotAddCommand;
use Bolt\Extension\IComeFromTheNet\Bookme\Model\Setup\Command\SlotToggleStatusCommand;
use Bolt\Extension\IComeFromTheNet\Bookme\Model\Setup\Command\RolloverTimeslotCommand;


use League\Tactician\Exception\Exception as BusException;
use Doctrine\DBAL\DBALException;


/**
 * Custom Exception for Calendar and Timeslot Setup
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class SetupException extends BookMeException implements BusException
{
    /**
     * @var mixed
     */
    public $oCommand;
    
    
   /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedToCreateNewTimeslot(SlotAddCommand $oCommand, DBALException $oDatabaseException = null)
    {
        $exception = new static(
            'Unable to create new timeslot for '. $oCommand->getSlotLength() .' ', 0 , $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedToCreateDays(SlotAddCommand $oCommand, DBALException $oDatabaseException = null)
    {
        $exception = new static(
            'Unable to create new timeslot days for '. $oCommand->getSlotLength() .' ', 0 , $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedToCreateYear(SlotAddCommand $oCommand, DBALException $oDatabaseException = null)
    {
        $exception = new static(
            'Unable to create new timeslot year for '. $oCommand->getSlotLength() .' ', 0 , $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedToToggleStatus(SlotToggleStatusCommand $oCommand, DBALException $oDatabaseException = null)
    {
        $exception = new static(
            'Unable to toggle timeslot status for '. $oCommand->getTimeSlotId() .' ', 0 , $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedToRollover(RolloverTimeslotCommand $oCommand, DBALException $oDatabaseException = null)
    {
        $exception = new static(
            'Unable to rollover timeslots into new calendar years', 0 , $oDatabaseException
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
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model;

use League\Tactician\Exception\Exception as BusException;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model\Command\SaveHolidayCommand;



/**
 * Custom Exception for Errors in HolidayRule Bundle
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class HolidayRuleException extends BookMeException implements BusException
{
    /**
     * @var mixed
     */
    public $oCommand;
    
    
    /**
     * @param SaveHolidayCommand $oCommand
     * @param DBALException     $oDatabaseException 
     *
     * @return static
     */
    public static function hasFailedToSaveHoliday(SaveHolidayCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'Unable to save holiday rule for holiday called id '.$oCommand->geHolidayDatetime()->getName().
            ' for date '.$oCommand->geHolidayDatetime()->format('d/m/Y'), null, $oDatabaseException
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
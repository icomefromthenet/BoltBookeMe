<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger;

use League\Tactician\Exception\Exception as BusException;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;


/**
 * Custom Exception for Ledger Bundle Errors
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class LedgerBundleException extends BookMeException implements BusException
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
    public static function appointmentNumberEmpty(CreateApptCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'The appointment id is empty unable to genertate a ledger user', null, $oDatabaseException
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
    public static function unableToCreateLedgerUser(CreateApptCommand $oCommand, DBALException $oDatabaseException= null, array $aErrors)
    {
        $sErrorMessage = '';
        
        if(isset($aErrors['msg'])) {
            foreach($aErrors['msg'] as $aReasons) {
                $sErrorMessage .= implode(',',$aReasons);
            }
        }
        
        $exception = new static(
            'Unable to create a new ledger user for the appointment with error '.$sErrorMessage, null, $oDatabaseException
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
    public static function unableToUpdateAppointmentWithLedgerUser(CreateApptCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'Unable to update appointment with new leger user ', null, $oDatabaseException
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
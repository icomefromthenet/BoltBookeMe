<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction;

use League\Tactician\Exception\Exception as BusException;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Doctrine\DBAL\DBALException;
//use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;


/**
 * Custom Exception for Transaction Bundle Errors
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class TransactionBundleException extends BookMeException implements BusException
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
     
    public static function appointmentNumberEmpty(CreateApptCommand $oCommand, DBALException $oDatabaseException= null)
    {
        $exception = new static(
            'The appointment id is empty unable to genertate a ledger user', null, $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    */
    
    
    
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
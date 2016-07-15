<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\CreateCustomerCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\ChangeCustomerCommand;


use League\Tactician\Exception\Exception as BusException;
use Doctrine\DBAL\DBALException;


/**
 * Custom Exception for Customer errors.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class CustomerException extends BookMeException implements BusException
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
    public static function hasFailedRegisterCustomer(CreateCustomerCommand $oCommand, DBALException $oDatabaseException)
    {
        $exception = new static(
            'Unable to create new customer with name '.$oCommand->sFirstName .' '.$oCommand->sLastName, 0 , $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
     /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedUpdateCustomer(ChangeCustomerCommand $oCommand, DBALException $oDatabaseException)
    {
        $exception = new static(
            'Unable to create new customer with at id '. $oCommand->iCustomerId .' name '.$oCommand->sFirstName .' '.$oCommand->sLastName, 0 , $oDatabaseException
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
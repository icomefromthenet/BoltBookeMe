<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\CreateCustomerCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerException;


/**
 * Used to create a new customer
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateCustomerHandler 
{
    
    /**
     * @var array   a map internal table names to external names
     */ 
    protected $aTableNames;
    
    /**
     * @var Doctrine\DBAL\Connection    the database adpater
     */ 
    protected $oDatabaseAdapter;
    
    
    
    public function __construct(array $aTableNames, Connection $oDatabaseAdapter)
    {
        $this->oDatabaseAdapter = $oDatabaseAdapter;
        $this->aTableNames      = $aTableNames;
        
        
    }
    
    
    public function handle(CreateCustomerCommand $oCommand)
    {
        $oDatabase              = $this->oDatabaseAdapter;
        $sCustomerTableName     = $this->aTableNames['bm_customer'];
        
        $oCustomer              = $oCommand;
        
        $aSaveCustomerSql        = [];
        $sSaveCustomerSql        = '';
     
        
        $aSaveCustomerSql[] = " INSERT INTO  $sCustomerTableName (`customer_id`, `created_on`, `updated_on`, `first_name`, `last_name`,   ";
        $aSaveCustomerSql[] = "                                  `email`, `mobile`, `landline`, `address_one`, `address_two`, `company_name`) ";
        $aSaveCustomerSql[] = " VALUES (null, now(), now(), :sFirstName, :sLastName, :sEmail, :sMobile, :sLandLine, :sAddressLineOne, :sAddressLineTwo, :sCompanyName ) ";
          
        $sSaveCustomerSql = implode(PHP_EOL,$aSaveCustomerSql);
      
        
        try {
            
	        $oIntType    = Type::getType(Type::INTEGER);
	        $oStringType = Type::getType(Type::STRING); 
	        
	        $aParams =  [
	            'sFirstName'        => $oCustomer->sFirstName,
	            'sLastName'         => $oCustomer->sLastName,
	            'sEmail'            => $oCustomer->sEmail,
	            'sMobile'           => $oCustomer->sMobile,
	            'sLandLine'         => $oCustomer->sLandline,
	            'sAddressLineOne'   => $oCustomer->sAddressLineOne,
	            'sAddressLineTwo'   => $oCustomer->sAddressLineTwo,
	            'sCompanyName'      => $oCustomer->sCompanyName,
	            
 	       ];
	        
	        $aTypes = [
	            'sFirstName' => $oStringType,
	            'sLastName'  => $oStringType,
	            'sEmail'     => $oStringType,
	            'sMobile'    => $oStringType,
	            'sLandLine'  => $oStringType,
	            'sAddressLineOne' => $oStringType,
	            'sAddressLineTwo' => $oStringType,
	            'sCompanyName' => $oStringType,
	        ];
	        
	    
	       	$iRowsAffected = $oDatabase->executeUpdate($sSaveCustomerSql, $aParams, $aTypes);
	        
	        if($iRowsAffected == 0) {
	            throw new DBALException('Could not save customer');
	        }
	        
	        $oCustomer->iCustomerId = $oDatabase->lastInsertId();
	             
	    }
	    catch(DBALException $e) {
	        throw CustomerException::hasFailedRegisterCustomer($oCommand,$e);
	    }
        
        
        return true;
    }
     
    
}
/* End of File */
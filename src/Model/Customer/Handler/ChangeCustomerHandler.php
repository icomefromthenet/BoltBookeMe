<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\ChangeCustomerCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerException;


/**
 * Used to update an existing customer
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ChangeCustomerHandler 
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
    
    
    public function handle(ChangeCustomerCommand $oCommand)
    {
        $oDatabase              = $this->oDatabaseAdapter;
        $sCustomerTableName     = $this->aTableNames['bm_customer'];
        
        $oCustomer              = $oCommand; 
        
        $aSaveCustomerSql        = [];
        $sSaveCustomerSql        = '';
     
        
        $aSaveCustomerSql[] = " UPDATE  $sCustomerTableName ";
        $aSaveCustomerSql[] = " SET `updated_on` = now(), `first_name` = :sFirstName, `last_name` = :sLastName, ";
        $aSaveCustomerSql[] = "      `address_one` = :sAddressLineOne,  `address_two` = :sAddressLineTwo,  `company_name` = :sCompanyName, ";
        $aSaveCustomerSql[] = "      `email` = :sEmail,  `mobile` = :sMobile, `landline` = :sLandLine ";
        $aSaveCustomerSql[] = " WHERE `customer_id` = :iCustomerId";
          
        $sSaveCustomerSql = implode(PHP_EOL,$aSaveCustomerSql);
      
        
        try {
            
	        $oIntType    = Type::getType(Type::INTEGER);
	        $oStringType = Type::getType(Type::STRING); 
	        
	        $aParams =  [
	            'iCustomerId'       => $oCustomer->iCustomerId,
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
	            'iCustomerId'=> $oIntType,
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
	        
	             
	    }
	    catch(DBALException $e) {
	        throw CustomerException::hasFailedUpdateCustomer($oCommand,$e);
	    }
        
        
        return true;
    }
     
    
}
/* End of File */
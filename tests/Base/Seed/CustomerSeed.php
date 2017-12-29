<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use DateTime;
use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class CustomerSeed extends BaseSeed
{
    
   
   protected $aNewCustomers;
    
    
    
    protected function createCustomer(
       $sFirstName,
       $sLastName,
       $sEmail,
       $sMobile,
       $sLandline,
       $sAddressLineOne,
       $sAddressLineTwo,
       $sCompanyName
        )
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        $sCustomerTableName = $aTableNames['bm_customer'];
        $aSaveCustomerSql   = [];
        $sSaveCustomerSql   = '';
     
        
        $aSaveCustomerSql[] = " INSERT INTO  $sCustomerTableName (`customer_id`, `created_on`, `updated_on`, `first_name`, `last_name`,   ";
        $aSaveCustomerSql[] = "                                  `email`, `mobile`, `landline`, `address_one`, `address_two`, `company_name`) ";
        $aSaveCustomerSql[] = " VALUES (null, now(), now(), :sFirstName, :sLastName, :sEmail, :sMobile, :sLandLine, :sAddressLineOne, :sAddressLineTwo, :sCompanyName ) ";
          
        $sSaveCustomerSql = implode(PHP_EOL,$aSaveCustomerSql);
      
            
	    $oIntType    = Type::getType(Type::INTEGER);
	    $oStringType = Type::getType(Type::STRING); 
	        
        $aParams =  [
            'sFirstName'        => $sFirstName,
            'sLastName'         => $sLastName,
            'sEmail'            => $sEmail,
            'sMobile'           => $sMobile,
            'sLandLine'         => $sLandline,
            'sAddressLineOne'   => $sAddressLineOne,
            'sAddressLineTwo'   => $sAddressLineTwo,
            'sCompanyName'      => $sCompanyName,
            
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
            throw new RuntimeException('Could not save customer');
        }
        
        return $oDatabase->lastInsertId();
    }
   
    
    
    protected function doExecuteSeed()
    {
        $aNewCustomers = [];
        
        foreach($this->aNewCustomers as $sKey => $aNewCustomer) {
            $aNewCustomers[$sKey] = $this->createCustomer(
                 $aNewCustomer['FIRST_NAME'], 
                 $aNewCustomer['LAST_NAME'], 
                 $aNewCustomer['EMAIL'], 
                 $aNewCustomer['MOBILE'], 
                 $aNewCustomer['LANDLINE'],
                 $aNewCustomer['ADDRESS_ONE'],
                 $aNewCustomer['ADDRESS_TWO'],
                 $aNewCustomer['COMPANY_NAME']
            );
            
        }
        
        return $aNewCustomers;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aNewCustomers)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aNewCustomers = $aNewCustomers;
   
    }
    
    
}
/* End of Class */
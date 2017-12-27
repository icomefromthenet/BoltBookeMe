<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Customer;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\CreateCustomerCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\ChangeCustomerCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;



class CustomerTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
      return;
   }  
   
   
    /**
    * @group Setup
    */ 
    public function testCustomerCommands()
    {
        
       $oCustomer =  $this->RegisterCustomerTest();
       
       $oCustomer = $this->UpdateCustomerTest($oCustomer);
    }
    
    
    protected function RegisterCustomerTest()
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
          try {
       
            $sFirstName = 'fName';
            $sLastName = 'lName';
            $sEmail = 'email@email.com.au';
            $sMobile = '040455555';
            $sLandline = '0258542154';
            $sAddressLineOne ='Address Line One';
            $sAddressLineTwo = 'Address Line Two';
            $sCompanyName ='ACompany';
            
            $oCommand  = new CreateCustomerCommand($sFirstName, $sLastName, $sEmail, $sMobile, $sLandline, $sAddressLineOne, $sAddressLineTwo, $sCompanyName);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iCustomerId = $oCommand->getCustomerId();
        
        $this->assertNotEmpty($iCustomerId,'The new customer command failed to return new customer database id');
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_customer 
                                            where customer_id = ? ",[$iCustomerId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iCustomerId,$aResult['customer_id'],'New customer could not be found in database');
        
        $this->assertEquals($oCommand->sFirstName, $aResult['first_name']);
        $this->assertEquals($oCommand->sLastName,$aResult['last_name']);
        $this->assertEquals($oCommand->sEmail,$aResult['email']);
        $this->assertEquals($oCommand->sMobile,$aResult['mobile']);
        $this->assertEquals($oCommand->sLandline,$aResult['landline']);
        $this->assertEquals($oCommand->sAddressLineOne,$aResult['address_one']);
        $this->assertEquals($oCommand->sAddressLineTwo,$aResult['address_two']);
        $this->assertEquals($oCommand->sCompanyName,$aResult['company_name']);
        $this->assertNotEmpty($aResult['created_on']);
        $this->assertNotEmpty($aResult['updated_on']);
        
        return $oCommand;
        
    }
    
    
    protected function UpdateCustomerTest($oCustomer)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
         try {
       
            $sFirstName = '2fName';
            $sLastName = '2lName';
            $sEmail = '2email@email.com.au';
            $sMobile = '2040455555';
            $sLandline = '20258542154';
            $sAddressLineOne ='2Address Line One';
            $sAddressLineTwo = '2Address Line Two';
            $sCompanyName ='2ACompany';
            
            $oCommand  = new ChangeCustomerCommand($oCustomer->getCustomerId(),$sFirstName, $sLastName, $sEmail, $sMobile, $sLandline, $sAddressLineOne, $sAddressLineTwo, $sCompanyName);
       
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iCustomerId = $oCommand->getCustomerId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_customer 
                                            where customer_id = ? ",[$iCustomerId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iCustomerId,$aResult['customer_id'],'New customer could not be found in database');
        
        $this->assertEquals($oCommand->sFirstName, $aResult['first_name']);
        $this->assertEquals($oCommand->sLastName,$aResult['last_name']);
        $this->assertEquals($oCommand->sEmail,$aResult['email']);
        $this->assertEquals($oCommand->sMobile,$aResult['mobile']);
        $this->assertEquals($oCommand->sLandline,$aResult['landline']);
        $this->assertEquals($oCommand->sAddressLineOne,$aResult['address_one']);
        $this->assertEquals($oCommand->sAddressLineTwo,$aResult['address_two']);
        $this->assertEquals($oCommand->sCompanyName,$aResult['company_name']);
        $this->assertNotEmpty($aResult['created_on']);
        $this->assertNotEmpty($aResult['updated_on']);
        
        return $oCommand;
        
        
    }
    
    
}
/* end of file */

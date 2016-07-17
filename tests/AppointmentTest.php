<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\ApptEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;



class AppointmentTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
      $iCustomerOneId     = $oService->createCustomer('Bob', 'Builder', 'bob@builder.com', '0404555555', '98172762', 'Bob Address Line One', 'Bob Address Line Two', 'Company One');
      $iCustomerTwoId     = $oService->createCustomer('Steve', 'Builder', 'seteve@builder.com', '0404555556', '98172762' , 'Steve Address Line One', 'Steve Address Line Two', 'Company two');
      $iCustomerThreeId   = $oService->createCustomer('Karen', 'Builder', 'karen@builder.com', '0404555557', '98172762' , 'Karen Address Line One', 'Karen Address Line Two', 'Company three');
      
      $this->aDatabaseId = [
        'customer_1' => $iCustomerOneId,
        'customer_2' => $iCustomerTwoId,
        'customer_3' => $iCustomerThreeId,
          
          
      ];
      
      return;
   }  
   
   
    /**
    * @group Setup
    */ 
    public function testAppointmentCommands()
    {
        
       $oApptId   =  $this->CreateAppointmentTest($this->aDatabaseId['customer_1']);
       
       
    }
    
    
    protected function CreateAppointmentTest($iCustomerId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
       
            $sInstructions = '.';
          
            
            $oCommand  = new CreateApptCommand($iCustomerId,$sInstructions);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $this->assertNotEmpty($iApptId,'The new appointment command failed to return new customer database id');
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'New customer could not be found in database');
        
        $this->assertEquals($oCommand->getInstructions(), $aResult['instructions']);
        $this->assertEquals('W',$aResult['status_code']);
      
        
        return $iApptId;
        
    }
    
   
    
    
}
/* end of file */

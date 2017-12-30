<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Appointment;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\ApptEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentNumberGenerator;


class AppointmentBasicTest extends ExtensionTest
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
    public function testAppointmentCommands()
    {
       // Test appointment Number Generator
       
        $this->AppointmentGeneratorTest();  
        
       // create appointments  
       
       $iApptCustomerOneId     =  $this->CreateAppointmentTest($this->aDatabaseId['customer_1']);
       $iApptCustomerTwoId     =  $this->CreateAppointmentTest($this->aDatabaseId['customer_2']);
       $iApptCustomerThreeId   =  $this->CreateAppointmentTest($this->aDatabaseId['customer_3']);
       
    }
    
    protected function AppointmentGeneratorTest()
    {
        
        $sPrefix   ='A';
        $sSuffix   ='#';
        $iStartingIndex = 200;
        
        $oGenerator = new AppointmentNumberGenerator($sPrefix,$sSuffix,$iStartingIndex);
        
        $sNewNumber = $oGenerator->getApptNumber(100);
        
        $this->assertEquals('A300#', $sNewNumber);
        
        
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
        
        $this->assertNotEmpty($iApptId,'The new appointment command failed to return new appt database id');
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'New appt could not be found in database');
        
        $this->assertEquals($oCommand->getInstructions(), $aResult['instructions']);
        $this->assertEquals('W',$aResult['status_code']);
        $this->assertEquals('A'.($iApptId+1000).'#',$aResult['appointment_no']);
        $this->assertEquals($oCommand->getAppointmentNumber(),$aResult['appointment_no']);
        
        return $iApptId;
        
    }
    
    
}
/* end of file */

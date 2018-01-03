<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Ledger;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock\MockApptHandlerFail;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock\MockApptHandlerSuccess;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Model\Decorator\NewApptDecorator;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;

class NewApptDecoratorTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
       
      $oNow      = $this->getNow();
      $oService  = $this->getTestAPI();
      $oDatabase = $this->getDatabaseAdapter();
      $aConfig   = $this->getAppConfig();
      
      $oDatabase->executeUpdate('
         UPDATE bolt_bm_appointment 
         SET ledger_user_id = null 
         WHERE appointment_id = ?'
         ,[$this->aDatabaseId['appt_customer_one_1']]
         ,[TYPE::INTEGER]);
      
   }
   
   
   public function testApptNumberDecerator()
   {
       
      $oContainer  = $this->getContainer();
      $oDatabase   = $this->getDatabaseAdapter();
      $oNow        = $this->getNow();
      $aConfig     = $this->getAppConfig();
      $aTableNames = $aConfig['tablenames'];
  
      // We need to force load the ledger container so it will
      // set the tablegateways for this component
      $oLedgerContauner = $oContainer['bm.ledger.container']; 
  
  
  
      $oGateway    = $oContainer['bm.tablegateway.proxycollection'];
      $oLogger     = $oContainer['logger.system'];

      $iTestApptId = $this->aDatabaseId['appt_customer_one_1'];
      $iCustomerId   = $this->aDatabaseId['customer_1'];
       
       
      $oMockApptHandler = new MockApptHandlerSuccess($iTestApptId);
       
      $oDecorator = new NewApptDecorator($oMockApptHandler, $aTableNames, $oDatabase, $oGateway, $oLogger, $oNow); 
      
      $oNewApptCommand = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand')
                              ->setMethods(['getAppointmentId'])
                              ->setConstructorArgs([$iCustomerId, 'no instructions'])
                              ->getMock();
      
      $oNewApptCommand->expects($this->once())
             ->method('getAppointmentId')
             ->will($this->returnValue($iTestApptId));
      
      
      $oDecorator->handle($oNewApptCommand);
      
      $iLegerUser = $oDatabase->fetchColumn('select ledger_user_id from bolt_bm_appointment where appointment_id = ?',[$iTestApptId],0);
      
      $this->assertNotEmpty($iLegerUser);
      
      $this->assertTrue($iLegerUser != $iTestApptId);
   }
   
}
/* End of File */
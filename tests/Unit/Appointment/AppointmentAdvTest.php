<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Appointment;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CancelApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\AssignApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CompleteApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\MoveApptWaitingCommand;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\ApptEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;




class AppointmentAdvTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      $oDatabase    = $this->getDatabaseAdapter();
      
     /*
      
      // Create some manual bookings
      
      $iMemberOneSchedule = $this->aDatabaseId['schedule_member_one'];
      
        
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),1,14);
      $oOpen->setTime(17,0,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),1,14);
      $oClose->setTime(17,20,0);
    
      $iBookingMemberOneFirst = $oService->takeManualBooking($iMemberOneSchedule,$oOpen,$oClose);
   
      
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),2,13);
      $oOpen->setTime(13,0,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),2,13);
      $oClose->setTime(13,20,0);
    
      $iBookingMemberOneSecond = $oService->takeManualBooking($iMemberOneSchedule,$oOpen,$oClose);
      
      // Create some customers
      
      $iCustomerOneId     = $this->aDatabaseId['customer_1'];
      $iCustomerTwoId     = $this->aDatabaseId['customer_2'];
      $iCustomerThreeId   = $this->aDatabaseId['customer_3'];
   
    
      // Create Some Appointments
   
      $iApptCustomerOneId        = $oService->createAppointment($iCustomerOneId,'First Job Instruction');
      $iApptCustomerTwoId        = $oService->createAppointment($iCustomerTwoId,'Second Job Instruction');
      $iApptCustomerOne2ndApptId = $oService->createAppointment($iCustomerOneId,'Third Job Instruction');
      
       // save identifiers for use below    
            
      $this->aDatabaseId = array_merge($this->aDatabaseId,[
        'booking_member_one_1'   => $iBookingMemberOneFirst,
        'booking_member_one_2'   => $iBookingMemberOneSecond,
        'appt_customer_one_1'      => $iApptCustomerOneId,
        'appt_customer_one_2'      => $iApptCustomerOne2ndApptId,
        'appt_customer_two_1'      => $iApptCustomerTwoId,
      ]);
      */
      
      
      
      $oDatabase->executeUpdate("
        update bolt_bm_appointment 
        set booking_id = null, status_code = 'W' 
        where 1=1");
      
      
      return;
   }  
   
   
    /**
    * @group Setup
    */ 
    public function testAppointmentCommands()
    {
       
       // This appt should be on the waiting list
       $iApptCustomerOneId      = $this->aDatabaseId['appt_customer_one_1'];
       $iApptCustomerOne2ndApptId = $this->aDatabaseId['appt_customer_one_2'];
       $iApptCustomerTwoId      = $this->aDatabaseId['appt_customer_two_1'];
       
       $iBookingMemberOneId     = $this->aDatabaseId['booking_member_one_1'];
       $iBookingMemberOne2ndId     = $this->aDatabaseId['booking_member_one_2'];
       
       // test cancel
       $this->CancelAppointmentTest($iApptCustomerOneId);    
       
       
       // test assign
       $this->AssignAppointmentTest($iApptCustomerTwoId,$iBookingMemberOneId);
       
       // test assign failes in wrong status
       $this->AssignApptFailsWrongStatusTest($iApptCustomerTwoId,$iBookingMemberOneId);
     
       // test complete appointment
       $this->CompleteAppointmentTest($iApptCustomerTwoId);
       
       $this->AssignAppointmentTest($iApptCustomerOne2ndApptId,$iBookingMemberOne2ndId);
       $this->MoveWaitingListTest($iApptCustomerOne2ndApptId);
       $this->MoveCanceledWaitingListTest($iApptCustomerOneId);
       
    }
    
   
    
    protected function CancelAppointmentTest($iApptId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $oCommand  = new CancelApptCommand($iApptId);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('C',$aResult['status_code']);
      
        
        return $iApptId;
        
    }
    
    protected function AssignAppointmentTest($iApptId, $iBookingId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $sInstructions = 'instructions are here';
            
            $oCommand  = new AssignApptCommand($iApptId,$iBookingId,$sInstructions);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('A',$aResult['status_code']);
        $this->assertEquals($iBookingId,$aResult['booking_id']);
        $this->assertEquals($sInstructions,$aResult['instructions']);
      
        
        return $iApptId;
        
    }
    
    protected function AssignApptFailsWrongStatusTest($iApptId, $iBookingId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $sInstructions = 'instructions are here';
            
            $oCommand  = new AssignApptCommand($iApptId,$iBookingId,$sInstructions);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
        catch(AppointmentException $e) {
           
           $this->assertEquals($e->getMessage(),'Unable to assign booking at id '.$oCommand->getBookingId().' to appointment at id '.$oCommand->getAppointmentId(). ' the appointment is either not found or not in correct status ');
            return;   
        }
        
        $this->assertFalse(true,'Should of failed appointment assignment');
        
    }
    
    protected function CompleteAppointmentTest($iApptId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $oCommand  = new CompleteApptCommand($iApptId);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('D',$aResult['status_code']);
        
        
      
        
        return $iApptId;
        
    }
    
    protected function MoveWaitingListTest($iApptId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $oCommand  = new MoveApptWaitingCommand($iApptId);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('W',$aResult['status_code']);
        $this->assertEmpty($aResult['booking_id']);
        
      
        
        return $iApptId;
        
    }
    
     protected function MoveCanceledWaitingListTest($iApptId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $oCommand  = new MoveApptWaitingCommand($iApptId);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('W',$aResult['status_code']);
        $this->assertEmpty($aResult['booking_id']);
        
      
        
        return $iApptId;
        
    }
    
}
/* end of file */

<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Voucher;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;



class VoucherTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
       
      $oNow      = $this->getNow();
      $oService  = $this->getTestAPI();
      $oDatabase = $this->getDatabaseAdapter();
      $aConfig   = $this->getAppConfig();
      
     $oDatabase->executeUpdate('DELETE FROM bolt_bm_voucher_instance WHERE 1=1');
     
   }
   
   
   public function testLoadVoucherService()
   {
     
      $oContainer = $this->getContainer();
      
      /** @var Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\VoucherNumbers  **/
      $oVoucherService = $oContainer['bm.voucher.service'];
      
      // Test Appointment Numbers
      
      $sVoucher  = $oVoucherService->getAppointmentNumber();
       
      $this->assertNotEmpty($sVoucher); 
      $this->assertEquals(0,strpos($sVoucher,'A'));
      
      
      // Test Sales Journals
    
      $sVoucher  = $oVoucherService->getSalesJournalNumber();
      
      
      $this->assertNotEmpty($sVoucher); 
      $this->assertEquals(0,strpos($sVoucher,'S'));
      
      // Test Payments Journals
    
      $sVoucher  = $oVoucherService->getPaymentJournalNumber();
      
      $this->assertNotEmpty($sVoucher); 
      $this->assertEquals(0,strpos($sVoucher,'D'));
      
      // Test General Journals
    
      $sVoucher  = $oVoucherService->getGeneralJournalNumber();
      
      
      $this->assertNotEmpty($sVoucher); 
      $this->assertEquals(0,strpos($sVoucher,'G'));
      
   }
   
   
   
   
}
/* End of Class */
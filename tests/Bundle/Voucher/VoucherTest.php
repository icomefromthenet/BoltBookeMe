<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Bundle\Voucher;

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
      
      //$oFixture         = new HolidayRuleFixture($oDatabase, $oService, $oNow);
      //$oScheduleFixture = new ScheduleFixture($oDatabase, $oService, $oNow);
      
      //$aGeneralFixture = $oScheduleFixture->runFixture($aConfig);
      //$aBundleFixture  = $oFixture->runFixture($aConfig);
      
    
      //$this->aDatabaseId = array_merge($aGeneralFixture, $aBundleFixture);
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
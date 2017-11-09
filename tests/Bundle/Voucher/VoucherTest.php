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
    
      $oGenerator = $oContainer['bm.voucher.generator'];
      
      $sVoucher  = $oGenerator
                    ->setVoucherByName('salues_journals_v1')
                    ->generate();
       
   }
   
   
   
   
}
/* End of Class */
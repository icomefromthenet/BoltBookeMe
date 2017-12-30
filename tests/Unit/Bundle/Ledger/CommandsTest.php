<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Ledger;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Bundle\Mock\MockApptHandlerFail;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Bundle\Mock\MockApptHandlerSuccess;


class CommandsTest extends ExtensionTest
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
   
   
   public function testApptNumberDecerator()
   {
       
       $oContainer = $this->getContainer();
       
       $oLedgerContainer = $oContainer['bm.ledger.container'];
       
       
       
       
   }
   
}
/* End of File */
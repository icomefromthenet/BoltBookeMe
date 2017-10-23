<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Bundle\HolidayRule;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture\HolidayRuleFixture;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture\ScheduleFixture;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model\Command\SaveHolidayCommand;

class SaveHolidayTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
       
      $oNow      = $this->getNow();
      $oService  = $this->getTestAPI();
      $oDatabase = $this->getDatabaseAdapter();
      $aConfig   = $this->getAppConfig();
      
      $oFixture         = new HolidayRuleFixture($oDatabase, $oService, $oNow);
      $oScheduleFixture = new ScheduleFixture($oDatabase, $oService, $oNow);
      
      $aGeneralFixture = $oScheduleFixture->runFixture($aConfig);
      $aBundleFixture  = $oFixture->runFixture($aConfig);
      
    
      $this->aDatabaseId = array_merge($aGeneralFixture, $aBundleFixture);
   }
   
   
   public function testSaveCommandProperties()
   {
      $iMemberOneScheduleId = $this->aDatabaseId['member_one_schedule'];
      
      $oComand = new SaveHolidayCommand();
      
    
    
       
   }
   
   
}
/* End of Class */
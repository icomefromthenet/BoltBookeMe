<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Bundle\HolidayRule;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture\HolidayRuleFixture;



class SaveHolidayTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
       
      $oNow      = $this->getNow();
      $oService  = $this->getTestAPI();
      $oDatabase = $this->getDatabaseAdapter();
      
      $oFixture = new HolidayRuleFixture($oDatabase, $oService, $oNow);
    
      $this->aDatabaseId = $oFixture->runFixture();    
   }
   
   
   public function testSaveCommandProperties()
   {
    
       
   }
   
   
}
/* End of Class */
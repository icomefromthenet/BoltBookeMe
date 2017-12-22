<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewScheduleSeed;

class NewScheduleFixture extends BaseFixture
{
 
    
    public function runFixture(array $aAppConfig)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
      
        $oNewScheduleSeed = new NewScheduleSeed($oDatabase, $aTableNames,$aAppConfig);
      
        return $oNewScheduleSeed->executeSeed();
        
    }
    
}
/* End of Class */

<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\AssignTeamSeed;


class AssignTeamFixture extends BaseFixture
{
 
    
    public function runFixture(array $aAppConfig)
    {
      
        $oDatabase       = $this->getDatabaseAdapter();
        $aTableNames     = $this->getTableNames();
        $oAssignTeamSeed = new AssignTeamSeed($oDatabase, $aTableNames, $aAppConfig);
      
        return $oAssignTeamSeed->executeSeed();
        
    }
    
}
/* End of Class */

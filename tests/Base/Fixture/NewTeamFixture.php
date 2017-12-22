<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewTeamSeed;

class NewTeamFixture extends BaseFixture
{
 
    
    public function runFixture(array $aAppConfig)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
      
        $oNewTeamSeed = new NewTeamSeed($oDatabase, $aTableNames,[
            'iTeamOne'  =>  ['TEAM_NAME' => 'Bob Team'],
            'iTeamTwo'  =>  ['TEAM_NAME' => 'Bill Team'],
            
        ]);
      
        return $oNewTeamSeed->executeSeed();
        
    }
    
}
/* End of Class */

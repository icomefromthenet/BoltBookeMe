<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class NewTeamSeed extends BaseSeed
{
    
   
   protected $aNewTeams;
    
    
    
    protected function createTeam($sTeamName)
    {
        $oDatabase         = $this->getDatabase();
        $aTableNames       = $this->getTableNames();
        
        $sTeamTableName    = $aTableNames['bm_schedule_team'];
        $iTeamId           = null;
        
        
        $sSql = " INSERT INTO $sTeamTableName (team_id, team_name ,registered_date) VALUES (null, ? ,NOW()) ";

    
        $oDatabase->executeUpdate($sSql, [$sTeamName], [Type::getType(Type::STRING)]);
        
        $iTeamId = $oDatabase->lastInsertId();
        
        if(empty($iTeamId)) {
            throw new \RuntimeException('Unable to create team');
        }
        
        return $iTeamId;
             
    }
   
    
    
    protected function doExecuteSeed()
    {
        $aTeams = [];
        
        foreach($this->aNewTeams as $sKey => $aTeam) {
            $aTeams[$sKey] = $this->createTeam($aTeam['TEAM_NAME']);
            
        }
        
        return $aTeams;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aNewTeams)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aNewTeams = $aNewTeams;
   
    }
    
    
}
/* End of Class */

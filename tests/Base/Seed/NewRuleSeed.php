<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class NewRuleSeed extends BaseSeed
{
    
   
   protected $aNewRules;
    
    
    
    protected function createRule()
    {
        $oDatabase         = $this->getDatabase();
        $aTableNames       = $this->getTableNames();
        
        $sTeamTableName    = $aTableNames['bm_schedule_team'];
        $iRuleId           = null;
        
        
        $sSql = " INSERT INTO $sTeamTableName (team_id, team_name ,registered_date) VALUES (null, ? ,NOW()) ";

    
        $oDatabase->executeUpdate($sSql, [$sTeamName], [Type::getType(Type::STRING)]);
        
        $iRuleId = $oDatabase->lastInsertId();
        
        if(empty($iRuleId)) {
            throw new \RuntimeException('Unable to create new Rule');
        }
        
        return $iRuleId;
             
    }
   
    
    
    protected function doExecuteSeed()
    {
        $aNewRules = [];
        
        foreach($this->aNewRules as $sKey => $aNewRule) {
            $aNewRules[$sKey] = $this->createRule(
                $aNewRule['TEAM_NAME']
                
            );
            
        }
        
        return $aNewRules;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aNewRules)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aNewRules = $aNewRules;
   
    }
    
    
}
/* End of Class */

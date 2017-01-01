<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractRepoQuery;

/**
 * Build Query for the Schedule Teams
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0  
 */ 
class TeamQueryBuilder extends AbstractRepoQuery
{
   
    
    public function orderByTeamNameAsc($sAlias)
    {
        $this->orderBy($this->getField($sAlias,'team_name'),'ASC');
        
        return $this;
    }
    
    public function orderByTeamNameDesc($sAlias)
    {
        $this->orderBy($this->getField($sAlias,'team_name'),'DESC');
        
        return $this;
    }
    
    
}
/* End of File */
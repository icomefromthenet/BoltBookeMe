<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter to schedules for a given team.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class TeamFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams                = $this->params;
        $oQuery                 = $this->getQueryBuilder();
        $sTeamMembersTable      = $oQuery->getTableName('bm_schedule_team_members');
        
        
        if(true === isset($aParams['iTeamId'])) {
        
            $oQuery->andWhere("EXISTS (
                    SELECT 1
                    FROM $sTeamMembersTable stm
                    WHERE stm.membership_id = ".$this->getField('membership_id',$this->getAlias())."
                    AND stm.team_id = :iTeamId
                
            )")
            ->setParameter('iTeamId',$aParams['iTeamId'],Type::INTEGER);
        }
        
    }
    
}
/* end of class */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable\Directive;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter to limit member who last schedule in cal year x
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class ScheduleTeamFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams            = $this->params;
        $oQuery             = $this->getQueryBuilder();
        $sAlias             = $this->getAlias();
        $sScheduleTeamTable = $oQuery->getTableName('bm_schedule_team_members');
        
        if(isset($aParams['iScheduleTeam']) && !empty($aParams['iScheduleTeam'])) {
            
            $oQuery->andWhere(
                    " EXISTS (
                        select st.team_id 
                        from $sScheduleTeamTable  st
                        where ".$oQuery->expr()->eq($this->getField($sAlias,'membership_id')
                                                    ,$this->getField('st','membership_id'))."
                        and  st.team_id = :iScheduleTeam) "
                    )
                   ->setParameter('iScheduleTeam',$aParams['iScheduleTeam']->getTeamId(),Type::INTEGER);
                  
        }
        
    }
    
}
/* end of class */
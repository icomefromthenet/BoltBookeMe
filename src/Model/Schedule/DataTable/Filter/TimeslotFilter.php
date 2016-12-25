<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter to schedules for a given timeslot
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class TimeslotFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams                = $this->params;
        $oQuery                 = $this->getQueryBuilder();
        
        if(true === isset($aParams['iTimeslotId'])) {
        
            $oQuery->andWhere($this->getField('timeslot_id',$this->getAlias())." = :iTimeslotId")
                   ->setParameter('iTimeslotId',$aParams['iTimeslotId'],Type::INTEGER);
        }
        
    }
    
}
/* End of Class */
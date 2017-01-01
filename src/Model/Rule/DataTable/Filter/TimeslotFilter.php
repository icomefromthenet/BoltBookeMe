<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter to limit rules that belong to a given timeslot
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class TimeslotFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams = $this->params;
        $oQuery  = $this->getQueryBuilder();
        $sAlias  = $this->getAlias(); 
        
        if(isset($aParams['iTimeslotId']) && !empty($aParams['iTimeslotId'])) {
            
            
            $oQuery->andWhere($oQuery->expr()->eq($this->getField($sAlias,'timeslot_id'),':iTimeslotId'))
                   ->setParameter('iTimeslotId',$aParams['iTimeslotId'],Type::INTEGER);
        }
        
    }
    
}
/* end of class */
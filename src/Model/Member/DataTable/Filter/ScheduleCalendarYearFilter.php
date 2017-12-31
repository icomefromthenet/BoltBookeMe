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
class ScheduleCalendarYearFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams = $this->params;
        $oQuery  = $this->getQueryBuilder();
        $sAlias  = Directive\JoinLastScheduleDirective::TABLE_ALIAS;
        
        if(isset($aParams['iCalYear']) && !empty($aParams['iCalYear'])) {
            
            $oQuery->andWhere($oQuery->expr()->eq($this->getField($sAlias,'calendar_year'),':iCalYear'))
                   ->setParameter('iCalYear',$aParams['iCalYear']->getCalendarYear(),Type::INTEGER);
                  
        }
        
    }
    
}
/* end of class */
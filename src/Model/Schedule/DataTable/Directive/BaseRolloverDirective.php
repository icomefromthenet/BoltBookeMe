<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\DataTable\Directive;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractDirective;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Default Fields in Rollover Search 
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class BaseRolloverDirective extends AbstractDirective
{
    
    public function build()
    {
            
        $oQuery         = $this->getQueryBuilder();
        $sScheduleYabl  = $oQuery->getTableName('bm_schedule'); 
        $sAlias         = $this->getAlias();
         

        $oQuery->select(
            $this->getField($sAlias, 'schedule_id'),
            $this->getField($sAlias, 'timeslot_id'),
            $this->getField($sAlias, 'membership_id'),
            $this->getField($sAlias, 'calendar_year'),
            $this->getField($sAlias, 'is_carryover'),
            $this->getField($sAlias, 'registered_date'),
            $this->getField($sAlias, 'close_date')
            
        )
        ->from($sRuleTableName,$sAlias);

    }
    
}
/* end of class */
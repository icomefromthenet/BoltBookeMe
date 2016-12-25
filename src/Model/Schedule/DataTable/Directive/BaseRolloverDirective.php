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
            $this->getField('schedule_id',$sAlias),
            $this->getField('timeslot_id',$sAlias),
            $this->getField('membership_id',$sAlias),
            $this->getField('calendar_year',$sAlias),
            $this->getField('is_carryover',$sAlias),
            $this->getField('registered_date',$sAlias),
            $this->getField('close_date',$sAlias)
            
        )
        ->from($sRuleTableName,$sAlias);

    }
    
}
/* end of class */
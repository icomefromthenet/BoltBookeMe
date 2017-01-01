<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable\Directive;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractDirective;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Fetch Last Schedule The member has
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class JoinLastScheduleDirective extends AbstractDirective
{
    
    const TABLE_ALIAS = 'curSch';
    
    
    public function build()
    {
            
        $oQuery                  = $this->getQueryBuilder();
        $sScheduleTable          = $oQuery->getTableName('bm_schedule'); 
        $sDefaultAlias           = $this->getAlias();    
        $sScheduleAlias          = self::TABLE_ALIAS;
        
        
        // Register join alias
        
        $oQuery->useAlias($sScheduleAlias,'bm_schedule');
        
        // Join table
    
        $oQuery->innerJoin(
            $sDefaultAlias
            ,"( SELECT dd.membership_id, dd.schedule_id, dd.cal_year, dd.is_carryover, dd.close_date
                FROM $sScheduleTable dd
                WHERE dd.cal_year = (SELECT MAX(kk.cal_year) 
                                    FROM $sScheduleTable kk 
                                    WHERE dd.membership_id = kk.membership_id)
             )"
            ,$sScheduleAlias
            ,$oQuery->expr()->eq($this->getField($sDefaultAlias,'membership_id'),$this->getField($sScheduleAlias,'membership_id'))
        )
        ->addSelect(
            $this->getField($sScheduleAlias, 'schedule_id'  , 'scheduleId'),
            $this->getField($sScheduleAlias, 'cal_year'     , 'calYear'),
            $this->getField($sScheduleAlias, 'is_carryover' , 'isCarryover'),
            $this->getField($sScheduleAlias, 'close_date'   , 'closeDate')
            
        ); 
        
    }
    
}
/* end of class */
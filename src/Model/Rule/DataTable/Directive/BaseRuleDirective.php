<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Directive;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractDirective;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Default Fields in Rule Table that form base of our search query
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class BaseRuleDirective extends AbstractDirective
{
    
    public function build()
    {
            
        $oQuery         = $this->getQueryBuilder();
        $sRuleTableName = $oQuery->getTableName('bm_rule'); 
        $sAlias         = $this->getAlias();
            
        $oQuery->select(
            $this->getField($sAlias,'rule_id','ruleId'),
            $this->getField($sAlias,'rule_type_id','ruleTypeId'),
            $this->getField($sAlias,'timeslot_id','timeslotId'),
            $this->getField($sAlias,'repeat_minute','repeatMinute'),
            $this->getField($sAlias,'repeat_hour','repeatHour'),
            $this->getField($sAlias,'repeat_dayofweek','repeatDayOfWeek'),
            $this->getField($sAlias,'repeat_dayofmonth','repeatDayOfMonth'),
            $this->getField($sAlias,'repeat_month','repeatMonth'),
            $this->getField($sAlias,'repeat_weekofyear','repeatWeekOfYear'),
            $this->getField($sAlias,'start_from','startFrom'),
            $this->getField($sAlias,'end_at','endAt'),
            $this->getField($sAlias,'open_slot','openSlot'),
            $this->getField($sAlias,'close_slot','closeSlot'),
            $this->getField($sAlias,'cal_year','calYear'),
            $this->getField($sAlias,'carry_from_id','carryFromId'),
            $this->getField($sAlias,'is_single_day','isSingleDay'),
            $this->getField($sAlias,'rule_name','ruleName'),
            $this->getField($sAlias,'rule_desc','ruleDesc')
        )
        ->from($sRuleTableName,$sAlias);

    }
    
}
/* end of class */
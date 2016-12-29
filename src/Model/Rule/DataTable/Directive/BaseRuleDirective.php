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
            $this->getField('rule_id',$sAlias,'ruleId'),
            $this->getField('rule_type_id',$sAlias, 'ruleTypeId'),
            $this->getField('timeslot_id',$sAlias,'timeslotId'),
            $this->getField('repeat_minute',$sAlias,'repeatMinute'),
            $this->getField('repeat_hour',$sAlias,'repeatHour'),
            $this->getField('repeat_dayofweek',$sAlias,'repeatDayOfWeek'),
            $this->getField('repeat_dayofmonth',$sAlias,'repeatDayOfMonth'),
            $this->getField('repeat_month',$sAlias,'repeatMonth'),
            $this->getField('repeat_weekofyear',$sAlias,'repeatWeekOfYear'),
            $this->getField('start_from',$sAlias,'startFrom'),
            $this->getField('end_at',$sAlias,'endAt'),
            $this->getField('open_slot',$sAlias,'openSlot'),
            $this->getField('close_slot',$sAlias,'closeSlot'),
            $this->getField('cal_year',$sAlias,'calYear'),
            $this->getField('carry_from_id',$sAlias,'carryFromId'),
            $this->getField('is_single_day',$sAlias,'isSingleDay'),
            $this->getField('rule_name',$sAlias,'ruleName'),
            $this->getField('rule_desc',$sAlias,'ruleDesc')
        )
        ->from($sRuleTableName,$sAlias);

    }
    
}
/* end of class */
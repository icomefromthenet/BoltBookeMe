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
            $this->getField('rule_id',$sAlias),
            $this->getField('rule_type_id',$sAlias),
            $this->getField('timeslot_id',$sAlias),
            $this->getField('repeat_minute',$sAlias),
            $this->getField('repeat_hour',$sAlias),
            $this->getField('repeat_dayofweek',$sAlias),
            $this->getField('repeat_dayofmonth',$sAlias),
            $this->getField('repeat_month',$sAlias),
            $this->getField('repeat_weekofyear',$sAlias),
            $this->getField('start_from',$sAlias),
            $this->getField('end_at',$sAlias),
            $this->getField('open_slot',$sAlias),
            $this->getField('close_slot',$sAlias),
            $this->getField('cal_year',$sAlias),
            $this->getField('carry_from_id',$sAlias),
            $this->getField('is_single_day',$sAlias),
            $this->getField('rule_name',$sAlias),
            $this->getField('rule_desc',$sAlias)
        )
        ->from($sRuleTableName,$sAlias);

    }
    
}
/* end of class */
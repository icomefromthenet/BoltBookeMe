<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable;

use Doctrine\DBAL\Types\Type;
use Bolt\Storage\Query\QueryInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQuery;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Filter;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Directive;


/**
 * A Query of the Rule Entity
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com> 
 * @since 1.0
 */
class RuleSearchQuery extends SelectQuery implements QueryInterface
{
   
   
 
 
    protected function setupDefaults()
    {
        
        # Set the ID Column of the resultset
        
        $this->setRowIdColumnName('rule_id');
        
        # Directives
       
        $this->addDirective(new Directive\BaseRuleDirective($this->getQueryBuilder(),$this->getContentType()));
        $this->addDirective(new Directive\JoinTimeslotDirective($this->getQueryBuilder(),$this->getContentType()));
        $this->addDirective(new Directive\JoinRuleTypeDirective($this->getQueryBuilder(),$this->getContentType()));
        
        
        # Filters
        
        $this->addFilter(new Filter\ApplyFromFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new Filter\EndBeforeFilter($this->getQueryBuilder(),$this->getContentType()));
        
        
        
        # Database to PHP Mapping
        
        $oInteger   = $this->getTypeFromFactory(TYPE::INTEGER);
        $oDate      = $this->getTypeFromFactory(TYPE::DATE);
        $oDateTime  = $this->getTypeFromFactory(TYPE::DATETIME);
        $oString    = $this->getTypeFromFactory(TYPE::STRING);
        $oBoolean   = $this->getTypeFromFactory(TYPE::BOOLEAN);
        
        // Rule Details    
        $this->addMap('rule_id', $oInteger);
        
        $this->addMap('repeat_minute',     $oString);
        $this->addMap('repeat_hour',       $oString);
        $this->addMap('repeat_dayofweek',  $oString);
        $this->addMap('repeat_dayofmonth', $oString);
        $this->addMap('repeat_month',      $oString);
        $this->addMap('repeat_weekofyear', $oString); 
        
        $this->addMap('rule_name',$oString);
        $this->addMap('rule_desc',$oString);
        $this->addMap('start_from', $oDateTime);
        $this->addMap('end_at', $oDateTime);
    
        $this->addMap('is_single_day',$oBoolean);
        $this->addMap('carry_from_id', $oBoolean);

        $this->addMap('cal_year',  $oInteger);
     
        
        // Rule Type Details
        $this->addMap('rule_type_id', $oInteger);
        $this->addMap('rule_code',$oString);
        
        
        // Timeslot Details
        $this->addMap('timeslot_id', $oInteger);
        $this->addMap('open_slot',  $oInteger);
        $this->addMap('close_slot',  $oInteger);
        $this->addMap('timeslot_length',  $oInteger);
        
        
        
    }
    
    
   
    
}
/* End of File */
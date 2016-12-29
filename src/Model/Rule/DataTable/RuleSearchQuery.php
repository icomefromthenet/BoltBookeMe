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
        
        $this->setRowIdColumnName('ruleId');
        
        # Directives
       
        $this->addDirective(new Directive\BaseRuleDirective($this->getQueryBuilder(),$this->getContentType()));
        $this->addDirective(new Directive\JoinTimeslotDirective($this->getQueryBuilder(),$this->getContentType()));
        $this->addDirective(new Directive\JoinRuleTypeDirective($this->getQueryBuilder(),$this->getContentType()));
        
        
        # Filters
        
        $this->addFilter(new Filter\ApplyFromFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new Filter\EndBeforeFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new Filter\TimeslotFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new Filter\RuleTypeFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new Filter\CalendarYearFilter($this->getQueryBuilder(),$this->getContentType()));
        
        # Database to PHP Mapping
        
        $oInteger   = $this->getTypeFromFactory(TYPE::INTEGER);
        $oDate      = $this->getTypeFromFactory(TYPE::DATE);
        $oDateTime  = $this->getTypeFromFactory(TYPE::DATETIME);
        $oString    = $this->getTypeFromFactory(TYPE::STRING);
        $oBoolean   = $this->getTypeFromFactory(TYPE::BOOLEAN);
        
        // Rule Details    
        $this->addMap('ruleId', $oInteger);
        
        $this->addMap('repeatMinute',     $oString);
        $this->addMap('repeatHour',       $oString);
        $this->addMap('repeatDayOfWeek',  $oString);
        $this->addMap('repeatDayOfMonth', $oString);
        $this->addMap('repeatMonth',      $oString);
        $this->addMap('repeatWeekOfYear', $oString); 
        
        $this->addMap('ruleName',$oString);
        $this->addMap('ruleDesc',$oString);
        $this->addMap('startFrom', $oDateTime);
        $this->addMap('endAt', $oDateTime);
    
        $this->addMap('isSingleDay',$oBoolean);
        $this->addMap('carryFromId', $oBoolean);

        $this->addMap('calYear',  $oInteger);
     
        
        // Rule Type Details
        $this->addMap('ruleTypeId', $oInteger);
        $this->addMap('ruleCode',$oString);
        
        
        // Timeslot Details
        $this->addMap('timeslotId', $oInteger);
        $this->addMap('openSlot',  $oInteger);
        $this->addMap('closeSlot',  $oInteger);
        $this->addMap('timeslotLength',  $oInteger);
        
        
        
    }
    
   
    
}
/* End of File */
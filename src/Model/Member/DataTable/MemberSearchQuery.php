<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable;

use Doctrine\DBAL\Types\Type;
use Bolt\Storage\Query\QueryInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQuery;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable\Filter;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable\Directive;


/**
 * A Query of the Schedule Member Entity
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com> 
 * @since 1.0
 */
class MemberSearchQuery extends SelectQuery implements QueryInterface
{
   
   
 
 
    protected function setupDefaults()
    {
        
        # Set the ID Column of the resultset
        
        $this->setRowIdColumnName('membershipId');
        
        # Directives
       
        $this->addDirective(new Directive\BaseRuleDirective($this->getQueryBuilder(),$this->getContentType()));
        $this->addDirective(new Directive\JoinLastScheduleDirective($this->getQueryBuilder(),$this->getContentType()));
        
        
        # Filters
        
        $this->addFilter(new Filter\CreatedFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new Filter\ScheduleCalendarYearFilter($this->getQueryBuilder(), $this->getContentType()));
        
        
        # Database to PHP Mapping
        
        $oInteger   = $this->getTypeFromFactory(TYPE::INTEGER);
        $oDate      = $this->getTypeFromFactory(TYPE::DATE);
        $oDateTime  = $this->getTypeFromFactory(TYPE::DATETIME);
        $oString    = $this->getTypeFromFactory(TYPE::STRING);
        $oBoolean   = $this->getTypeFromFactory(TYPE::BOOLEAN);
        
        // Member Details  
        
        $this->addMap('membershipId', $oInteger);
        $this->addMap('registeredDate', $oDate);
        $this->addMap('memberName', $oString);
        
        // Last Schedule Details
        
        $this->addMap('scheduleId',$oInteger);
        $this->addMap('calYear',$oInteger);
        $this->addMap('isCarryover',$oBoolean);
        $this->addMap('closeDate',$oDate);
        
        
        
    }
    
   
    
}
/* End of File */
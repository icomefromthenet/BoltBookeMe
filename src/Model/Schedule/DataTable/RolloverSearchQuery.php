<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\DataTable;

use Doctrine\DBAL\Types\Type;
use Bolt\Storage\Query\QueryInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQuery;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\DataTable\Filter;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\DataTable\Directive;


/**
 * A Query of the Schedules need a Rollover
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com> 
 * @since 1.0
 */
class RolloverSearchQuery extends SelectQuery implements QueryInterface
{
   
   
 
 
    protected function setupDefaults()
    {
        
        # Set the ID Column of the resultset
        
        $this->setRowIdColumnName('schedule_id');
        
        # Directives
       
        $this->addDirective(new Directive\BaseRolloverDirective($this->getQueryBuilder(),$this->getContentType()));
        $this->addDirective(new Directive\JoinMemberDetailDirective($this->getQueryBuilder(),$this->getContentType()));
        $this->addDirective(new Directive\JoinTimeslotDirective($this->getQueryBuilder(),$this->getContentType()));
        
        
        # Filters
        
        $this->addFilter(new Filter\CalendarYearFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new Filter\TeamFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new Filter\TimeslotFilter($this->getQueryBuilder(),$this->getContentType()));
        
        
        
        # Database to PHP Mapping
        
        $oInteger   = $this->getTypeFromFactory(TYPE::INTEGER);
        $oDate      = $this->getTypeFromFactory(TYPE::DATE);
        $oDateTime  = $this->getTypeFromFactory(TYPE::DATETIME);
        $oString    = $this->getTypeFromFactory(TYPE::STRING);
        $oBoolean   = $this->getTypeFromFactory(TYPE::BOOLEAN);
        
        // Schedule Details    
        $this->addMap('schedule_id', $oInteger);
        $this->addMap('timeslot_id', $oInteger);
        $this->addMap('membership_id', $oInteger);
        
        $this->addMap('calendar_year', $oInteger);
        $this->addMap('is_carryover', $oBoolean);
        $this->addMap('registered_date', $oDate);
        $this->addMap('close_date', $oDate);
        
        // Member Details
        $this->addMap('member_name',$oString);
        $this->addMap('member_registered_date',$oDate);
        
        // Timeslot Details
        $this->addMap('timeslot_length', $oInteger);
        
    }
    
    
   
    
}
/* End of File */
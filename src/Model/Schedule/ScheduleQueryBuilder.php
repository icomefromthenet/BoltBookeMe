<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractRepoQuery;

/**
 * Build Query for the Schedules
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0  
 */ 
class ScheduleQueryBuilder extends AbstractRepoQuery
{
   
    public function withMember($sAlias, $sMemberAlias)
    {
        
        
        
        return $this;
    }
    
    
    public function filterActiveSchedules($sAlias)
    {
        
     
        return $this;
    }
    
    public function filterInCalendarYear($iCalYear)
    {
        
        
        return $this;
        
    }
    
    
}
/* End of File */
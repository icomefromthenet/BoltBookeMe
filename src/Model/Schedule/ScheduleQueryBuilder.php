<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule;

use DateTime;
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
        $sMemberTable = $this->getRepository()->getTableName('bm_schedule_membership');
        
        $this->join($sAlias,
                    $sMemberTable,
                    $sMemberAlias,
                    $this->expr()->eq($this->getField($sAlias,'membership_id'),
                                      $this->getField($sMemberAlias,'membership_id')));
                                      
        $this->addSelect($this->getField($sMemberAlias,'member_name','member_name'));
        
        
        return $this;
    }
    
    
    public function withBoltUser($sMemberAlias, $sUserAlias)
    {
        
        $this->leftJoin($sMemberAlias,
                    'bolt_users',
                    $sUserAlias,
                    $this->expr()->eq($this->getField($sMemberAlias,'bolt_user_id'),
                                      $this->getField($sUserAlias,'id')));
                                      
        $this->addSelect($this->getField($sUserAlias,'email','member_email'));
        
        
        return $this;   
        
    }
    
    
    public function filterByCalendarYear($sAlias, $iCalYear)
    {
        
        $this->andWhere($this->expr()->eq($this->getField($sAlias,'calendar_year'),':iCalYear'))
            ->setParameter(':iCalYear',$iCalYear, Type::INTEGER);
        
     
        return $this;
    }
    
    public function scheduleNotClosed($sAlias, DateTime $oDateTime)
    {
        $this->filterByCalendarYear($sAlias, $oDateTime->format('Y'))
            ->andWhere($this->expr()->lte($this->getField($sAlias,'close_date'),':sCloseDate'))
            ->setParameter('sCloseDate',$oDateTime,Type::DATE);
        
        return $this;
    }
    
    
}
/* End of File */
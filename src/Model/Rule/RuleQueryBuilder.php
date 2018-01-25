<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractRepoQuery;

/**
 * Build Query for the Schedule Rules
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0  
 */ 
class RuleQueryBuilder extends AbstractRepoQuery
{
   
   /**
    * Find rules that belong to a particualr schedule
    * 
    * @return RuleQueryBuilder
    * @param    string      $sRuleTableAlias    The sql alias assigned to the bm_rule table during query
    * @param    integer     $iScheduleId        The database id of the bm_schedule
    */ 
   public function filterBySchedule($sRuleTableAlias, $iScheduleId)
   {
       $sRuleScheduleTable = $this->getRepository()->getTableName('bm_rule_schedule');
       
       $this->andWhere(
           "EXISTS (
            SELECT 1 
            FROM $sRuleScheduleTable rs
            WHERE rs.schedule_id = :iScheduleId
            AND ".$this->getField($sRuleTableAlias,'rule_id')."= rs.rule_id ");
            
        $this->setParameter(':iScheduleId',$iScheduleId, TYPE::INTEGER);
            
        return $this;
   }
   
   
   /**
    * Filters rule by their Type
    * 
    * @return RuleQueryBuilder
    * @param    string      $sRuleTableAlias    The sql alias of the bm_rule table
    * @param    integer     $iRuleTypeId        The database id from bm_rule_type
    */ 
   public function filterByRuleType($sRuleTableAlias, $iRuleTypeId)
   {
       $this->andWhere($this->getField($sRuleTableAlias,'rule_type_id').'= :iRuleTypeId');
       
       $this->setParameter(':iRuleTypeId', $iRuleTypeId , TYPE::INTEGER);
       
       return $this;
   }
   
    
    
}
/* End of File */
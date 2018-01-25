<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;



class RuleRepository extends ReadOnlyRepository implements ObjectRepository
{
   
    
    /**
     * Creates a new QueryBuilder instance that is prepopulated for this rule.
     *
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias = null)
    {
        if (null === $alias) {
            $alias = $this->getAlias();
        }

        if (empty($alias)) {
            $select = '*';
        } else {
            $select = $alias . '.*';
        }

        $oQuery = new RuleQueryBuilder($this->getEntityManager()->getConnection(), $this);
        $oQuery->select($select)->from($this->getTableName(), $alias);
            
        return $oQuery;
    }
    
    
    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object|null The object.
     */
    public function find($id)
    {
        $qb = $this->getLoadQuery();
        $result = $qb->where($this->getAlias() . '.rule_id = :iRuleId')
            ->setParameter(':iRuleId', $id)
            ->execute()
            ->fetch();

        if ($result) {
            return $this->hydrate($result, $qb);
        }

        return false;
        
    }
    
    
    public function getRulesForSchedule($iScheduleId, $iRuleType= null)
    {
        $qb = $this->getLoadQuery();
        
        if($iRuleType !== null) {
            $qb->filterByRuleType($this->getAlias(), $iRuleType);
        }
        
        $qb->filterBySchedule($this->getAlias(), $iScheduleId);
        
        
        $result = $qb->execute()->fetch();

        if ($result) {
            return $this->hydrate($result, $qb);
        }

        return false;

        
        
    }
    
    
    public function getHoldiayRulesForSchedule($iScheduleId)
    {
        return $this->getRulesForSchedule($iScheduleId,3);
    }

    public function getBreakRulesForSchedule($iScheduleId)
    {
        return $this->getRulesForSchedule($iScheduleId, 2);
    }
    
    public function getWorkdayRulesForSchedule($iScheduleId)
    {
        return $this->getRulesForSchedule($iScheduleId, 1);
    }
    
    public function getSurchargeRulesForSchedule($iScheduleId)
    {
        return $this->getRulesForSchedule($iScheduleId, 5);
    }
    
    public function getOvertimeRulesForSchedule()
    {
        return $this->getRulesForSchedule($iScheduleId, 4);
    }
   
}
/* End of Class */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;



class RuleTypeRepository extends ReadOnlyRepository implements ObjectRepository
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

        $oQuery = new RuleTypeQueryBuilder($this->getEntityManager()->getConnection(), $this);
        $oQuery->select($select)->from($this->getTableName(), $alias);
            
        return $oQuery;
    }
    
    
    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object|bool The object.
     */
    public function find($id)
    {
        $qb = $this->getLoadQuery();
        $result = $qb->where($this->getAlias() . '.rule_type_id = :iRuleTypeId')
            ->setParameter(':iRuleTypeId', $id)
            ->execute()
            ->fetch();

        if ($result) {
            return $this->hydrate($result, $qb);
        }

        return false;
        
    }

    /**
     * Find all Rule Types
     * 
     * @return object|bool The object. 
     */ 
    public function findRuleTypes()
    {
        $qb = $this->getLoadQuery();
        $sAlias = $this->getAlias();       
        $result = $qb->execute()
                     ->fetchAll(\PDO::FETCH_ASSOC);

        if ($result) {
            return $this->hydrateAll($result, $qb);
        }

        return false;
        
    }
   
}
/* End of Class */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;
use Bolt\Events\HydrationEvent;



class ScheduleRepository extends ReadOnlyRepository implements ObjectRepository
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

        $oQuery = new ScheduleQueryBuilder($this->getEntityManager()->getConnection(), $this);
        $oQuery->select($select)
               ->from($this->getTableName(), $alias)
               ->withMember('m')
               ->withBoltUser('m','u');
            
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
        $result = $qb->where($this->getAlias() . '.schedule_id = :iScheduleId')
            ->setParameter(':iScheduleId', $id)
            ->execute()
            ->fetch();

        if ($result) {
            return $this->hydrate($result, $qb);
        }

        return false;
        
    }


    public function findAllActiveSchedulesInCalYear($iCalYear)
    {
        $qb = $this->getLoadQuery();
    
    
        $result = $qb
                    ->filterByCalendarYear($this->getAlias(),$iCalYear)
                    ->filterByScheduleOpen($this->getAlias())
                    ->execute()
                    ->fetchAll();
 
        if ($result) {
            return $this->hydrateAll($result, $qb);
        }

        return false;
        
    }
  
  
    public function findAllSchedulesInCalYear($iCalYear)
    {
        $qb = $this->getLoadQuery();
    
    
        $result = $qb
                    ->filterByScheduleOpen($this->getAlias())       
                    ->filterByCalendarYear($this->getAlias(),$iCalYear)
                    ->execute()
                    ->fetchAll();
 
        if ($result) {
            return $this->hydrateAll($result, $qb);
        }


        return false;
        
    }
   
   
    public function findScheduleForUsername($sUsername, $iCalYear)
    {
        $qb = $this->getLoadQuery();
    
    
        $result = $qb
                    ->filterByUsername('u', $sUsername)
                    ->filterByCalendarYear($this->getAlias(),$iCalYear)       
                    ->execute()
                    ->fetch();
 
        if ($result) {
            return $this->hydrate($result, $qb);
        }


        return false;
        
        
        
    }
    
}
/* End of Class */
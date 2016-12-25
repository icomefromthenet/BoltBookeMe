<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;
use Bolt\Storage\Mapping\ClassMetadata;


class CalendarYearRepository extends ReadOnlyRepository implements ObjectRepository
{
   
   
    /**
     * Initializes a new Repository.
     *
     * @param EntityManager $em            The EntityManager to use.
     * @param ClassMetadata $classMetadata The class descriptor.
     */
    public function __construct($em, ClassMetadata $classMetadata)
    {
        var_dump(get_class($classMetadata));
        
        parent::__construct($em,$classMetadata);
        
    }
   
    
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

        $oQuery = new CalendarYearQueryBuilder($this->getEntityManager()->getConnection(), $this);
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
        $sAlias = $this->getAlias();
        $result = $qb->withCurrentYearColumn($sAlias)
                    ->where($sAlias.'.y = :iCalYear')
                    ->setParameter(':iCalYear', $id)
                    ->execute()
                    ->fetch();

        if ($result) {
            return $this->hydrate($result, $qb);
        }

        return false;
        
    }

    /**
     * Return a collection of all calendar years
     * in this collection
     *  
     * @return 
     */
    public function findAllCalYears()
    {
        $qb = $this->getLoadQuery();
        $sAlias = $this->getAlias();       
        $result = $qb->withCurrentYearColumn($sAlias)
                     ->execute()
                     ->fetch();

        if ($result) {
            return $this->hydrate($result, $qb);
        }

        return false;
        
    }
   
}
/* End of Class */
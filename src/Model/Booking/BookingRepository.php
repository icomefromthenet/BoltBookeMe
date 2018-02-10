<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;



class BookingRepository extends ReadOnlyRepository implements ObjectRepository
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

        $oQuery = new BookingQueryBuilder($this->getEntityManager()->getConnection(), $this);
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
        $result = $qb->where($this->getAlias() . '.booking_id = :iBookingId')
            ->setParameter('iBookingId', $id)
            ->execute()
            ->fetch();

        if ($result) {
            return $this->hydrate($result, $qb);
        }

        return false;
        
    }

  
   
}
/* End of Class */
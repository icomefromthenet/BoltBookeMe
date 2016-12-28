<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractRepoQuery;

/**
 * Build Query for the Schedule Calendar
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0  
 */ 
class TimeslotQueryBuilder extends AbstractRepoQuery
{
   
    /**
     * Order the query by the slot length putting longer slots at top of result
     * 
     * @return $this
     * @param string $sAlias
     */ 
    public function orderBySlotLengthDesc($sAlias)
    {
        $this->orderBy($this->getField($sAlias,'timeslot_length'),'DESC');
        
        return $this;
    }
    
    /**
     * Hide the inactive slots from this result
     * 
     * @return this
     * @param string    $sAlias
     */ 
    public function hideInactiveSlots($sAlias)
    {
        $this->andWhere($this->getField($sAlias,'is_active_slot').'= 1');
        
        return $this;
    }
    
}
/* End of File */
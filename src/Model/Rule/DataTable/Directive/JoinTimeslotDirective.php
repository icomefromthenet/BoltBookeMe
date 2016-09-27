<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Directive;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractDirective;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Fetch timeslot details for opening and closing slot
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class JoinTimeslotDirective extends AbstractDirective
{
    
    const TIMESLOT_TABLE_ALIAS = 'tslot';
    
    
    public function build()
    {
            
        $oQuery                  = $this->getQueryBuilder();
        $sTimeslotTable          = $oQuery->getTableName('bm_timeslot'); 
        $sDefaultAlias           = $this->getAlias();    
        $sTimeslotAlias          = self::TIMESLOT_TABLE_ALIAS;
        
        // Register join alias
        
        $oQuery->useAlias($sTimeslotAlias,'bm_timeslot');
        
        // Join table
        
        $oQuery->innerJoin(
            $sDefaultAlias
            ,$sTimeslotTable
            ,$sTimeslotAlias
            ,$oQuery->expr()->eq($this->getField('timeslot_id',$sDefaultAlias),$this->getField('timeslot_id',$sTimeslotAlias))
        )
        ->addSelect($this->getField('timeslot_length',$sTimeslotAlias)); 
        
    }
    
}
/* end of class */
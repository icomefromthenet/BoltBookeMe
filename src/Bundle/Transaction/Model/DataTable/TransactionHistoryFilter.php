<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filters related to Transaction History
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class TransactionHistoryFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams = $this->params;
        $oQuery  = $this->getQueryBuilder();
        $sAlais  = $this->getAlias();
        
        if(isset($aParams['oCreatedAfter']) && is_a($aParams['oCreatedAfter'],'\DateTime') ) {
            
            $oQuery->andWhere($oQuery->expr()->gte($this->getField($sAlais,'registered_date'),':oCreatedAfter'))
                   ->setParameter('oCreatedAfter',$aParams['oCreatedAfter'],Type::DATE);
        }
        
        if(isset($aParams['oCreatedBefore']) && is_a($aParams['oCreatedBefore'],'\DateTime') ) {
            
            $oQuery->andWhere($oQuery->expr()->lte($this->getField($sAlais,'registered_date'),':oCreatedBefore'))
                   ->setParameter('oCreatedBefore',$aParams['oCreatedBefore'],Type::DATE);
        }
        
         if(isset($aParams['iCreatedYear']) && !empty($aParams['iCreatedYear'])) {
            
            $oQuery->andWhere($oQuery->expr()->eq('year('.$this->getField($sAlais,'registered_date').')',':iCreatedYear'))
                   ->setParameter('iCreatedYear',$aParams['iCreatedYear'],Type::INTEGER);
        }
        
        
    }
    
}
/* end of class */
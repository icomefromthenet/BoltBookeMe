<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Pimple;
use Bolt\Storage\Query\QueryResultset;

/**
 * Execute a select Query and also has a short cut to pull query from the container.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class SelectQueryHandler 
{
    
    /**
     * @var Pimple
     */ 
    protected $oContainer;
    
    
    /**
     * Return the bolt di container
     * 
     * @return Pimple
     */ 
    protected function getContainer()
    {
        return $this->oContainer;
    }
    
    
    
    public function __construct(Pimple $oContainer)
    {
        $this->oContainer = $oContainer;
    }
    
    
    /**
     * @param SelectQuery $oSelectQuery
     *
     * @return QueryResultset
     */
    public function executeQuery(SelectQuery $oSelectQuery, array $aParams = [])
    {
        
        $oResult    = new QueryResultset();
        $oPlatofrm  = $oSelectQuery->getQueryBuilder()->getConnection()->getDatabasePlatform();
        $sIdColumn  = $oSelectQuery->getRowIdColumnName();
       
        
        // Build Query 
        $oQueryBuilder = $oSelectQuery->setParameters($aParams)->build();    
        
        // Execute The Query
        $oSTH         = $oQueryBuilder->execute();
        
        // Map each result into set but convert to PHP values using mapping
        while($aData = $oSTH->fetch()) {
            
            foreach($aData as $sKey => &$mValue) {
                if($oSelectQuery->hasMap($sKey)) {
                    $mValue = $oSelectQuery->getMap($sKey)->convertToPHPValue($mValue,$oPlatofrm);
                }
            }    
            
            $oResult->add($aData,$aData[$sIdColumn]);
        }
        
        
        return $oResult;
        
    }
    
    /**
     * Return new instance of the select Query
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQuery
     */ 
    public function getQuery($sShortName)
    {
        return $this->getContainer()->offsetGet('bm.query.'.$sShortName);
    }
    
}
/* End of Class */
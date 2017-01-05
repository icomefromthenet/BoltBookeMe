<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Bolt\Storage\Query\QueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ActionRoute;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;

/**
 * Override the original as we want to pass the query builder into each filter so
 * need to pass it into QueryParameterParser
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
abstract class SelectQueryWithRoutes implements QueryInterface
{
    
    /**
     * @var Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */ 
    protected $oUrl;
   
    /**
     * @var array[ActionRoute]
     */ 
    protected $aRawRoutes;
   
    /**
     * Adds a new route.
     * 
     * @return this
     * @param ActionRoute
     */ 
    protected function addActionRoute(ActionRoute $sRouteName)
    {
        $this->aRawRoutes[$sRouteName->getRouteName()] = $sRouteName;
        
        return $this;
    }
    
    /**
     * Remove the route.
     * 
     * @return this
     * @param string the route name
     */ 
    protected function removeActionRoute($sRouteName)
    {
        if(!$this->hasActionRoute($sRouteName)) {
            throw new BookMeException('The route '.$sRouteName.' not found in this SelectQueryWithRoutes');
        }
        
        unset($this->aRawRoutes[$sRouteName]);
        
        return $this;
    }
    
    /**
     * Extension to the default hook onRowMappingComplete which used to add
     * custom link actions to each result
     * 
     * Child of this SelectQuery to still be able to use the hook.
     */ 
    abstract protected function doRowMappingComplete();
   
   
    /**
     * Check if a route with Name X exists.
     * 
     * @return boolean true if exists
     * @param  string   the route name
     */ 
    protected function hasActionRoute($sRouteName)
    {
        return isset($this->aRawRoutes[$sRouteName]);
    }
   
    /**
     * Constructor.
     *
     * @param QueryBuilder  $qb
     * @param string        $sAlias     The Table Alias that was used on entity table          
     */
    public function __construct(QueryBuilder $qb, $sAlias, UrlGeneratorInterface $oUrl)
    {
        $this->oUrl         = $oUrl;
        $this->aRawRoutes   = [];
        
        parent::__construct($sql, $sAlias);
    
    }


    


    /**
     * Use this hook to merge urls with this row
     * 
     * @return array of data given in first argument
     * @param array the data read from the databases
     */ 
    public function onRowMappingComplete(array $aData)
    {
        $sIdField = $this->getRowIdColumnName();
        
        if(isset($aData['links'])) {
            throw new BookMeException('The entity with id field '.$sIdField.' has links already');
        }
        
        $aData['links'] = [];
        
        foreach($this->aRawRoutes as $sRouteName => $oRoute) {
            $aData['links'][] = [
              'rel'  => $sRouteName,
              'link' => $oRoute->getUrl($this->oUrl, $aData),
            ];
        }
        
        // Execute the hook extension
        
        $this->doRowMappingComplete();
        
    }


}
/* End of Class */

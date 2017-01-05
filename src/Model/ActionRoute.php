<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Base Class for link used in the SelectQueryWithRoutes.
 * 
 * A child implement self::getUrl(), this should return a full url that can be
 * sent to the client.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
abstract class ActionRoute
{
    /**
     * @var string the route name 
     */ 
    protected $sRouteName;
    
    
    
    public function __construct($sRouteName)
    {
        $this->sRouteName = $sRouteName;    
    }
    
    /**
     * Return the route bind name
     * 
     * @return string 
     */ 
    public function getRouteName()
    {
        return $this->sRouteName;
    }
    
    /**
     * Generate a url form a route name and the entity data
     * 
     * This merge routeName + entity using the UrlGenerator
     * 
     * @return string the full url
     */ 
    abstract public function getUrl(UrlGeneratorInterface $oGenerator, array $aRow);

   

}
/* End of Class */
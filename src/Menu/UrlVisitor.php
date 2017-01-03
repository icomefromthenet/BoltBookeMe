<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Menu;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuException;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuGroup;

/**
 * Convert Names route into Urls.
 * 
 * @author <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class UrlVisitor implements MenuVisitorInterface
{
    
    /**
     * @var Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */ 
    protected $oUrlGenerator; 
    
    /**
     * @var params
     */ 
    protected $aParams;
    
    
    
    public function __construct(UrlGeneratorInterface $oUrlGenerator, array $aParams)
    {
        $this->oUrlGenerator = $oUrlGenerator;
        $this->aParams       = $aParams;
        
    }
    
    
    public function visitMenuGroup(MenuGroup $oGroup)
    {
        return null;
    }

    
    
    public function visitMenuItem(MenuItem $oItem)
    {
        $sRouteName = $oItem->getRouteName();
        
        try {
            
            $sUrl = $this->oUrlGenerator->generate($sRouteName,$this->aParams);
        
            
        } catch (\Exception $e) {
            throw MenuException::routeFailedToGenerate($oItem,$e);
        }
        
        
        return $sUrl;
    }
    
}
/* End of class */
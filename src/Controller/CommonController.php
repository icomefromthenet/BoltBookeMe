<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Controller;

use Silex\Application;
use Bolt\Extension\ExtensionInterface;

/**
 * Common controller
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
abstract class CommonController
{
    /** 
     * @var array The extension's configuration parameters
     */ 
    protected $aConfig;

    /**
     * @var Pimple
     */ 
    protected $oContainer;
    
    /**
     * @var ExtensionInterface
     */ 
    protected $oExtension;
    
    
    public function __construct(array $aConfig, Application $oContainer, ExtensionInterface $oExtension)
    {
        $this->aConfig    = $aConfig;
        $this->oContainer = $oContainer;
        $this->oExtension = $oExtension;
    }
    
    protected function getExtension()
    {
        return $this->oExtension;
    }

    protected function getExtensionConfig()
    {
        return $this->aConfig;
    }


    protected function getDatabaseAdapter()
    {
        return $this->oContainer->offsetGet('db');
    }
    
    protected function getCommandBus()
    {
        return $this->oContainer->offsetGet('bm.commandBus');
    }
    
    protected function getNow()
    {
        return $this->oContainer->offsetGet('bm.now');
    }
    
    protected function getFlash()
    {
        return $this->oContainer->offsetGet('logger.flash');
    }
    
    /**
     * Load a menu builder from DI container 
     *  
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuBuilder
     * @param string    $sMenuKey   The di name
     */
    protected function getMenu($sMenuKey)
    {
        return $this->oContainer->offsetGet('bm.menu.'.$sMenuKey);
    }
    
}
/* End of Calendar Admin Controller */

<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Controller;

use Silex\Application;

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
    
    
    
    public function __construct(array $aConfig, Application $oContainer)
    {
        $this->aConfig    = $aConfig;
        $this->oContainer = $oContainer;
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
    
}
/* End of Calendar Admin Controller */

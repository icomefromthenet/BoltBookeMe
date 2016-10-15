<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Controller;

use Silex\Application;
use Bolt\Extension\ExtensionInterface;

/**
 * Common controller
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
trait  CommonControllerTrait
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
     *  Must be defined under the namespace bm.menu
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuBuilder
     * @param string    $sMenuKey   The di name
     */
    protected function getMenu($sMenuKey)
    {
        return $this->oContainer->offsetGet('bm.menu.'.$sMenuKey);
    }
    
    
    /**
     * Load a Datatable 
     * 
     * Must be defined under the namespace bm.datatable.table
     *  
     * @return Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableConfigInterface
     * @param string    $sDataTableKey   The di name of the datatable
     */
    protected function getDataTable($sDataTableKey)
    {
        return $this->oContainer->offsetGet('bm.datatable.table.'.$sDataTableKey);
    }
    
    /**
     * Loads a Form 
     * 
     * Must be defined under the namespace bl.form.x
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookeMe\Form\Build\FormContainer
     * 
     */ 
    protected function getForm($sFormKey)
    {
        return $this->oContainer->offsetGet('bm.form.'.$sFormKey);
    }
}
/* End of Calendar Admin Controller */

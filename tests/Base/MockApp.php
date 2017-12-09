<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base;

use Bolt\Application;
use Bolt\Filesystem\Handler\Directory;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;

class MockApp extends Application
{
    
     /**
     * @deprecated Deprecated since 3.0, to be removed in 4.0. Use {@see ControllerEvents::MOUNT} instead.
     */
    public function initExtensions()
    {
        
        $oExtensionBaseDir = new Directory($this['filesystem'],'extensions://'.BOOKME_EXTENSION_PATH);
        $oWebDir           = new Directory($this['filesystem'],'extensions://'.BOOKME_EXTENSION_PATH.'/web');
        
        $this['extensions']->add(new BookMeExtension(),$oExtensionBaseDir,$oWebDir);
        //$this['extensions']->addManagedExtensions();
        //$this['extensions']->register($this);
    }
    
    
    
}
/* End of File */
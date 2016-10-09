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
   
    use CommonControllerTrait;
   
    
    public function __construct(array $aConfig, Application $oContainer, ExtensionInterface $oExtension)
    {
        $this->aConfig    = $aConfig;
        $this->oContainer = $oContainer;
        $this->oExtension = $oExtension;
    }
    
   
}
/* End of Calendar Admin Controller */

<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\AuditTrail;

use Silex\Application;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\SimpleBundle;


class AuditTrailBundle extends SimpleBundle
{
    
    
    protected function registerServices(Application $app)
    {
    
        $parentProviders = parent::getServiceProviders();
        $localProviders = [
    
        ];
        

        return array_merge($parentProviders,$localProviders);
        
    }
    
    
    //--------------------------------------------------------------------------
    # Assets
   
    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {
        return [
            // Web assets that will be loaded in the frontend
        
          
            // Web assets that will be loaded in the backend
          
          
        ];
    }

    //--------------------------------------------------------------------------
    # Twig Extensions

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return ['bundle/AuditTrail/Resources/view' => ['namespace' => 'AuditTrail']];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
            
        ];
    }
    
    
    
}
/* End of Class */
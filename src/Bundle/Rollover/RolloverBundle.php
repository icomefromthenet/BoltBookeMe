<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover;

use DateTime;
use Silex\Application;
use Bolt\Events\CronEvents;
use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\SimpleBundle;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover\Provider;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover\Controller;



class RolloverBundle extends SimpleBundle
{
    
    use DatabaseSchemaTrait;
    
    
    public function getServiceProviders() {
    
        $aConfig = $this->getConfig();

        $localProviders = [
             new Provider\RolloverMenuProvider($aConfig),
             new Provider\RolloverDataTableProvider($aConfig),
             new Provider\RolloverCommandBusProvider($aConfig),
             new Provider\RolloverFormProvider($aConfig),
        ];
    
        return $localProviders;
    }
    
    
    
    protected function registerServices(Application $app)
    {
         $this->extendDatabaseSchemaServices();
       
       
         parent::registerServices($app);
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
        
        return ['/src/Bundle/Rollover/Resources/view' => ['namespace' => 'Rollover']];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
            
        ];
    }
    
    //--------------------------------------------------------------------------
    # Database 
    
    
     /**
     * {@inheritdoc}
     */
    protected function registerExtensionTables()
    {
        
        return [
       
            
        ];
    
        
    }
    
    
    
    
    //--------------------------------------------------------------------------
    # Menu Entires and Routes

    /**
     * {@inheritdoc}
     *
     * Extending the backend menu:
     *
     * You can provide new Backend sites with their own menu option and template.
     *
     * Here we will add a new route to the system and register the menu option in the backend.
     *
     * You'll find the new menu option under "Extras".
     */
    protected function registerMenuEntries()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * Mount the ExampleController class to all routes that match '/example/url/*'
     *
     * To see specific bindings between route and controller method see 'connect()'
     * function in the ExampleController class.
     */
    protected function registerFrontendControllers()
    {
       return [];
    }

   
    /**
     * {@inheritdoc}
     */
    protected function registerBackendControllers()
    {
        $app = $this->getContainer();
        $config = $this->getConfig();
      
        return [
            'extend/bookme/rollover/rollover' =>  new Controller\RolloverController($config,$app,$this),
                            
        ];
        
        
    }
    
   

    //--------------------------------------------------------------------------
}
/* End of Class */
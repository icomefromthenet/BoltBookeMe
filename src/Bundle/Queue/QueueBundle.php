<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue;

use DateTime;
use Silex\Application;
use Bolt\Events\CronEvents;
use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\SimpleBundle;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema\QueueTransitionTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema\QueueTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema\QueueMonitorTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Controller;



class QueueBundle extends SimpleBundle
{
    
    use DatabaseSchemaTrait;
    
    public function getServiceProviders() {
    
        $aConfig = $this->getConfig();

        $localProviders = [
             new Provider\QueueServiceProvider($aConfig),
             new Provider\QueueMenuProvider($aConfig),
             new Provider\QueueDataTableProvider($aConfig),
             new Provider\QueueCommandBusProvider($aConfig),
             new Provider\QueueFormProvider($aConfig),
        ];
    
        return $localProviders;
    }
    
    protected function registerServices(Application $app)
    {
         $this->extendDatabaseSchemaServices();
       
         // register monitor
         $app['dispatcher']->addListener(CronEvents::CRON_HOURLY, 'Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\MonitorWorker');
       
        // register purge
        $app['dispatcher']->addListener(CronEvents::CRON_WEEKLY, 'Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\PurgeWorker');
       
       
         // register processor 
         $app['dispatcher']->addListener(CronEvents::CRON_HOURLY, 'Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\RebuildWorker');
       
       
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
        
        return ['/src/Bundle/Queue/Resources/view' => ['namespace' => 'Queue']];
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
          'bm_queue_monitor'    => QueueMonitorTable::class,
          'bm_queue'            => QueueTable::class,
          'bm_queue_transition' => QueueTransitionTable::class,
            
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
          'extend/bookme/queue/activities' =>  new Controller\QueueActivityController($config,$app,$this),
          'extend/bookme/queue/monitor'    =>  new Controller\QueueMonitorController($config,$app,$this),
          'extend/bookme/queue'            =>  new Controller\QueueJobController($config,$app,$this),
                            
        ];
        
        
    }
    
   

    //--------------------------------------------------------------------------
}
/* End of Class */
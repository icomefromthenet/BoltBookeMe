<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue;

use DateTime;
use Silex\Application;
use LaterJob\Exception as LaterJobException;
use Bolt\Events\CronEvents;
use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\SimpleBundle;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema\QueueTransitionTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema\QueueTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema\QueueMonitorTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Controller;
use  Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;


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
        ];
    
        return $localProviders;
    }
    
    protected function registerServices(Application $app)
    {
         $this->extendDatabaseSchemaServices();
       
         // register monitor
         $app['dispatcher']->addListener(CronEvents::CRON_HOURLY, array($this, 'executeMonitorWorker'));
       
         // register processor 
         $app['dispatcher']->addListener(CronEvents::CRON_HOURLY, array($this, 'executeRebuildWorker'));
       
       
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
          
        ];
        
        
    }
    
    //--------------------------------------------------------------------------
    # Register Workers
    
    public function executeRebuildWorker()
    {
        $oQueue = $this->getContainer()->offsetGet('bm.queue.queue');
        $oNow   = $this->getContainer()->offsetGet('bm.now');
        $oBus   = $this->getContainer()->offsetGet('bm.commandBus');
        $worker = $oQueue->worker();
        
        
        try {
            // start the worker with the assigned date
            $worker->start($oNow);
            
            $allocator = $worker->receive($oNow);
            
            $handle = $worker->getId();
            
            foreach($allocator as $job) {
            
                try {
                    
                    // since time can pass between job finishing we need
                    // use a new datetime so we know how much has passed since
                    // started processing
                    $job->start($handle, new DateTime());
                 
                    $iScheduleId = $job->getData();
                 
                    // Create new Refresh Command
                    $oCommand = new RefreshScheduleCommand($iScheduleId,false);
                    
                    // Pass command to bus for procesing, will throw schedueException
                    // if unable to process this schedule or validate failure
                    $oBus->handle($oCommand);
                    
                    
                    // Mark this job as finished, using new date time
                    // so we know how much time has passed
                    $job->finish($handle,new DateTime());
                }
                catch(LaterJobException $e) {
                    
                    if($job->getRetryCount() > 0) {
                        $job->error($handle,new DateTime(),$e->getMessage());    
                    }
                    else {
                        $job->fail($handle,new DateTime(),$e->getMessage());    
                    }
                }
                catch (ScheduleException $e) {
                    if($job->getRetryCount() > 0) {
                        $job->error($handle,new DateTime(),$e->getMessage());    
                    }
                    else {
                        $job->fail($handle,new DateTime(),$e->getMessage());    
                    }
                }
                catch(ValidationException $e) {
                     // validation failure this job will never process os
                     // lets fail it now.
                     $job->fail($handle,new DateTime(),$e->getMessage().' For Schedule at::'.$iScheduleId);  
                }
                
            }
            
            // finish the worker with a new date so we know how much
            // time has passed since start time
            $worker->finish(new DateTime());
            
        } catch(LaterJobException $e) {
            $worker->error($handle,new DateTime(),$e->getMessage());
            throw $e;            
        }

    }
    
    public function executeMonitorWorker()
    {
        
        
    }

    //--------------------------------------------------------------------------
}
/* End of Class */
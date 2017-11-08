<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use LaterJob\Log\LogSubscriber;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\RebuildWorker;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\PurgeWorker;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\MonitorWorker;

use Symfony\Component\EventDispatcher\EventDispatcher;
use LaterJob\Queue,
    LaterJob\Log\MonologBridge,
    LaterJob\UUID,
    LaterJob\Util\MersenneRandom,
    LaterJob\Loader\ConfigLoader,
    LaterJob\Loader\ModelLoader,
    LaterJob\Loader\EventSubscriber;

/**
 * Wrapper for the later job Queue Service Provider
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class QueueServiceProvider  implements ServiceProviderInterface
{
      
    const EVENT_DISPATCHER  = '.event.dispatcher';
    
    const UUID_GENERATOR    = '.uuid.generator';
    
    const QUEUE             = '.queue';
    
    const LOG_BRIDGE        = '.log.bridge';
    
    const LOADER_CONFIG     = '.loader.config';
    
    const LOADER_MODEL      = '.loader.model';
    
    const LOADER_EVENTS     = '.loader.events';
    
    const OPTIONS           = '.options';
    
    const QUERY_LOG         = '.query_log'; 
    
    /** @var array */
    protected $config;
    
    
    protected $index;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        
        $this->index = 'bm.queue';
        
    }

 
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
       
        $aConfig   = $this->config;
        $index     = $this->index;
        
        # expects options to be part of the container
        $aConfig['queue']['db'] = [
            'transition_table' => $aConfig['tablenames']['bm_queue_transition'],
            'queue_table'      => $aConfig['tablenames']['bm_queue'],
            'monitor_table'    => $aConfig['tablenames']['bm_queue_monitor'], 
        ];
      
        $app[$this->index.self::OPTIONS] = $aConfig['queue'];
      
      
        # Override services to use bolt equivalents
        $app[$this->index.self::LOG_BRIDGE] = $app->share(function($app) use ($aConfig) {
           return $app['logger.system'];
        });
        
        $app[$this->index.self::EVENT_DISPATCHER] = $app->share(function ($app) use ($aConfig) {
            return $app['dispatcher'];
        });
        
        $app[$this->index.self::QUERY_LOG] = $app->share(function($app) use ($aConfig){
            return new LogSubscriber($app['logger.system']);
        });   
        
        
        $app[$this->index.'.worker.rebuild'] = $app->share(function($app) use ($aConfig){
            return new RebuildWorker($app[$this->index.self::QUEUE], $app['bm.now'], $app['bm.commandBus']);
        }); 
        
        $app[$this->index.'.worker.monitor'] = $app->share(function($app) use ($aConfig){
            return new MonitorWorker($app[$this->index.self::QUEUE], $app['bm.now']);
        }); 
        
        $app[$this->index.'.worker.purge'] = $app->share(function($app) use ($aConfig){
            return new PurgeWorker($app[$this->index.self::QUEUE], $app['bm.now'], $aConfig['queue']['worker']['purge_days']);
        }); 
        
        $app['laterjob.api.formatters.job'] = $app->share(function() {
            return new \LaterJobApi\Formatter\JobFormatter();
        });
                
                    
        $app['laterjob.api.formatters.activity'] = $app->share(function() {
            return new \LaterJobApi\Formatter\ActivityFormatter();
        });
                
                    
        $app['laterjob.api.formatters.monitor'] = $app->share(function(){
            return new \LaterJobApi\Formatter\MonitorFormatter();
        });
        
        
        $app[$this->index.self::UUID_GENERATOR] = function () use ($app)  {
            return new UUID(new MersenneRandom());
        };
        
        
        $app[$this->index.self::LOADER_CONFIG] = function() use ($app){
            return new ConfigLoader();
        };
        
        $app[$this->index.self::LOADER_EVENTS] = function() use ($app){
            return new EventSubscriber();
        };
        
        $app[$this->index.self::LOADER_MODEL] = function() use ($app){
            return new ModelLoader($app['db']);
        };
        
        $app[$this->index.self::QUEUE] = function($name) use ($app,$index){
            
             $event  = $app[$index.QueueServiceProvider::EVENT_DISPATCHER]; 
             $log    = $app[$index.QueueServiceProvider::LOG_BRIDGE];
             $option = $app[$index.QueueServiceProvider::OPTIONS];
             $uuid   = $app[$index.QueueServiceProvider::UUID_GENERATOR];
             $config = $app[$index.QueueServiceProvider::LOADER_CONFIG];
             $model  = $app[$index.QueueServiceProvider::LOADER_MODEL];
             $events = $app[$index.QueueServiceProvider::LOADER_EVENTS];
             
             
             return new Queue($event,$log,$option,$uuid,$config,$model,$events);
            
        };
        
        
       
        
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
       $app[$this->index.self::EVENT_DISPATCHER]->addSubscriber($app[$this->index.self::QUERY_LOG]); 
    }
}
/* End of Service Provider */
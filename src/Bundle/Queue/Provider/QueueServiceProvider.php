<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use LaterJobApi\Provider\QueueServiceProvider as BaseQueueServiceProvider;
use LaterJob\Log\LogSubscriber;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\RebuildWorker;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\PurgeWorker;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Worker\MonitorWorker;

/**
 * Wrapper for the later job Queue Service Provider
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class QueueServiceProvider extends BaseQueueServiceProvider implements ServiceProviderInterface
{
    /** @var array */
    private $config;


    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        
        parent::__construct('bm.queue');
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
       
        $aConfig   = $this->config;
        
        # expects options to be part of the container
        $aConfig['queue']['db'] = [
            'transition_table' => $aConfig['tablenames']['bm_queue_transition'],
            'queue_table'      => $aConfig['tablenames']['bm_queue'],
            'monitor_table'    => $aConfig['tablenames']['bm_queue_monitor'], 
        ];
      
        $app[$this->index.self::OPTIONS] = $aConfig['queue'];
      
      
        # regester the parent implementation
        parent::register($app);
      
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
       
        
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
      return parent::boot($app);
    }
}
/* End of Service Provider */
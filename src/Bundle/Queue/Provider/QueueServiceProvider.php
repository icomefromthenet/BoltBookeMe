<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use LaterJobApi\Provider\QueueServiceProvider as BaseQueueServiceProvider;
use DBALGateway\Feature\StreamQueryLogger;


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
        $app[$this->index.'.options'] = $aConfig['queue'];
      
        $app[$this->index.'.options']['db'] = [
            'transition_table' => $aConfig['tablenames']['bm_queue_transition'],
            'queue_table'      => $aConfig['tablenames']['bm_queue'],
            'monitor_table'    => $aConfig['tablenames']['bm_queue_monitor'], 
        ];
      
        # regester the parent implementation
        parent::register($app);
      
        # Override services to use bolt equivalents
        $app[$this->index.self::LOG_BRIDGE] = $app->share(function() use ($app) {
           return $app['logger.system'];
        });
        
        $app[$this->index.self::EVENT_DISPATCHER] = $app->share(function ($name) use ($app) {
            return $app['dispatcher'];
        });
        
        $app[$this->index.self::QUERY_LOG] = $app->share(function() use ($app){
            return new StreamQueryLogger($app['logger.system']);
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
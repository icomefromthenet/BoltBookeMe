<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\RefreshScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Model\RefreshScheduleDecorator;


/**
 * Bootstrap the Command Bus extenstion for the Schedule Rebuild Queue
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class QueueCommandBusProvider implements ServiceProviderInterface
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
    }

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $aConfig   = $this->config;
       
        
        $app['bm.model.schedule.handler.refresh'] = $app->extend('bm.model.schedule.handler.refresh',
            function(RefreshScheduleHandler $oRefreshHandler, Application $container) use ($aConfig){
                return new RefreshScheduleDecorator($oRefreshHandler, 
                                                  $aConfig['tablenames'], 
                                                  $container['db'], 
                                                  $container['bm.queue.queue'],
                                                  $container['bm.now']);
        });
       
       
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
/* End of Service Provider */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;


/**
 * Bootstrap the Command Bus used for order operations.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class OrderCommandBusProvider implements ServiceProviderInterface
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
        
       $app->extend('bm.commandBus.map', function ($aCommands, $c) use ($aConfig) {
           
          return $aCommands;
         
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
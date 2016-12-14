<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;



/**
 * Bootstrap the Command Bus extenstion for the Schedule Rebuild Queue
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class RolloverCommandBusProvider implements ServiceProviderInterface
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
       
       
       
       
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
/* End of Service Provider */
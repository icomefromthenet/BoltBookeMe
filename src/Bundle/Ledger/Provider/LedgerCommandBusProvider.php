<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Model\Decorator\NewApptDecorator;



/**
 * Bootstrap the Command Bus used for booking operations.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class LedgerCommandBusProvider implements ServiceProviderInterface
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
        
        $app->extend('bm.model.appointment.handler.create', function ($oDefaultHandler, $c) use ($aConfig) {
           
            $aTableNames = $aConfig['tablenames'];
            $oDatabase   = $c['db'];
            $oGateway    = $c['bm.tablegateway.proxycollection'];
            $oLogger     = $c['logger.system'];
            $oNow        = $c['bm.now'];
            
            return new NewApptDecorator($oDefaultHandler, $aTableNames, $oDatabase, $oGateway, $oLogger, $oNow);
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
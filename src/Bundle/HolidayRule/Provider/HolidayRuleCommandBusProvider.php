<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Handler\SaveHolidayHandler;


/**
 * Bootstrap the Command Bus extenstion for the HolidayRule generator
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class HolidayRuleCommandBusProvider implements ServiceProviderInterface
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
       
    
        
        $app['bm.holidayrule.model.handler.addyear'] = $app->share(function(Application $container) use ($aConfig){
            return new SaveHolidayHandler($aConfig['tablenames'],$container['db']);
        });
        
    
    
        $app['bm.commandBus.locator'] = $app->extend('bm.commandBus.locator',
            function(HandlerLocator $oLocator, Application $container) use ($aConfig){
                
                $oLocator->addMapping(SaveHolidayHandler::class, 'bm.holidayrule.model.handler.addyear');
    
                return $oLocator;
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
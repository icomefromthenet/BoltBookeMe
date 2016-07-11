<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Valitron\Validator;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Cron\CronToQuery;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Cron\SegmentParser;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Cron\SlotFinder;

/**
 * Bootstrap any custom validation methods
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class CronParseProvider implements ServiceProviderInterface
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
      
        $aConfig = $this->config; 
        
        # Cron to Query
        $app['bm.slotFinder'] = function($c) use ($aConfig) {
            return new SlotFinder($c['logger.system'], $c['db'], $aConfig['tablenames']);
        };
        
        $app['bm.cronSegmentParser'] = function($c) use ($aConfig) {
          return new SegmentParser($c['logger.system']);  
        };
    
        $app['bm.cronToQuery'] = function($c) use ($aConfig) {
            return new CronToQuery($c['logger.system'], $c['db'], $aConfig['tablenames'], $c['bm.cronSegmentParser'],$c['bm.slotFinder']);
        }; 
            
       
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        
        
    }
}
/* End of Service Provider */
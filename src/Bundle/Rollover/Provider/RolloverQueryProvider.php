<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover\DataTable\RolloverSearchQuery;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover\DataTable\RolloverSearchQueryBuilder;


/**
 * Bootstrap Rollover Search Filter Query. 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class SearchQueryProvider implements ServiceProviderInterface
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
        
        
        $app['bm.query.rollover'] = function($c) use ($aConfig) {
            
            return new RolloverSearchQuery(new RolloverSearchQueryBuilder($c['db'],$aConfig['tablenames']),'r');
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
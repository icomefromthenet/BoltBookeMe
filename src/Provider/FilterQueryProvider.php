<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleQuery;

/**
 * Bootstrap Filter Query classes found in Bolt\Extension\IComeFromTheNet\BookMe\Model\XXXXX
 *
 * These classes can be extended though container to add new filters.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class FilterQueryProvider implements ServiceProviderInterface
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
        
        // These are not shared so they will create new instance on each call
        // Other extension can extend using PIMPLE::extend to add new filters into the query
        
        $app['bm.query.rule'] = function($c) use ($aConfig) {
            
            return $c['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleEntity')->createRuleQuery(); 
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
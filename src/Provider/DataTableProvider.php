<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\DenseFormat;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\RuleDataTable;

/**
 * Bootstrap The DataTable(s) for the Queue Bundle
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class DataTableProvider implements ServiceProviderInterface
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
        
        
         $app['bm.datatable.output'] = function($c) {
             return new StringOutput(new DenseFormat());
         };
        
        
        
        $app['bm.datatable.table.rule'] = $app->share(function($c) use ($aConfig) {
          
            $sDataUrl = $c['url_generator']->generate('bookme-rule-search');
          
            return new RuleDataTable($c['bm.datatable.output'],$sDataUrl);
             
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

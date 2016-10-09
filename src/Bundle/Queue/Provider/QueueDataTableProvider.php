<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Model\QueueActivityDataTable;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\DenseFormat;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;

/**
 * Bootstrap The DataTable(s) for the Queue Bundle
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class QueueDataTableProvider implements ServiceProviderInterface
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
        
        
         $app['bm.datatable.table.queue-activity'] = $app->share(function($c) use ($aConfig) {
          
            $sDataUrl = $c['url_generator']->generate('bookme-queue-list');
          
            return new QueueActivityDataTable($c['bm.datatable.output'],$sDataUrl);
             
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
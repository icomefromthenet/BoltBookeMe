<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualFieldsDecorator;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SchemaManagerDecorator;


class StorageExtensionsProvider implements ServiceProviderInterface
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
    
    
    public function register(Application $app)
    {
        
        $app['schema'] = $app->share(
            $app->extend(
                'schema',
                function ($oManager) use ($app) {
                    $oMgr = new SchemaManagerDecorator($app);
                    $oMgr->setApp($app); // base class has app as private :-( 
                    
                    return $oMgr;
                }
            )
        );   
        
        
        $app['storage.metadata'] = $app->share(
            $app->extend(
                'storage.metadata',
                function ($oMetaDataDriver) use ($app) {
                    $meta = new VirtualFieldsDecorator(
                        $app['schema'],
                        $app['config']->get('contenttypes'),
                        $app['config']->get('taxonomy'),
                        $app['storage.typemap'],
                        $app['storage.namingstrategy']
                    );
    
                    return $meta;
                }
            )
        );

  
    }

    public function boot(Application $app)
    {
 
    }
}
/* End of Class */

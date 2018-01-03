<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualFieldsDecorator;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SchemaManagerDecorator;
use DBALGateway\Table\GatewayProxyCollection;

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
        $aConfig = $this->config; 
       
       
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
                        $app['storage.config.contenttypes'],
                        $app['storage.config.taxonomy'],
                        $app['storage.typemap'],
                        $app['storage.namingstrategy']
                    );
    
                    return $meta;
                }
            )
        );
        
        
        // Gateway Proxy 
        
        $app['bm.tablegateway.proxycollection'] = $app->share(function($c) use ($aConfig) {
            
            /**@var Doctrine\DBAL\Schema\Schema **/
            $oSchema = $c['schema']->getSchema();
            
            return new GatewayProxyCollection($oSchema);
       
         });
        
        // Register Repoisotry Extensions
        
        
        $app['bm.repo.timeslot'] = $app->share(function($c) use ($aConfig) {
            
            $oTimeSlotRepo = $c['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotEntity'); 
            $oTimeSlotRepo->setTableMap($aConfig['tablenames']);
            
            return $oTimeSlotRepo;
          
        });
        
        $app['bm.repo.ruletype'] = $app->share(function($c) use ($aConfig) {
            
             $oRuleTypeRepo = $c['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeEntity'); 
             $oRuleTypeRepo->setTableMap($aConfig['tablenames']);
             
             return $oRuleTypeRepo;
        
        });
        
        $app['bm.repo.calyear'] = $app->share(function($c) use ($aConfig) {
            
            $oCalYearRepo  = $c['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearEntity');     
            $oCalYearRepo->setTableMap($aConfig['tablenames']);
            
            return $oCalYearRepo;
    
        });
        
        $app['bm.repo.team'] = $app->share(function($c) use ($aConfig) {
            
            $oTeamTypeRepo = $c['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamEntity'); 
            $oTeamTypeRepo->setTableMap($aConfig['tablenames']);
    
            return $oTeamTypeRepo;
        });
        
        $app['bm.repo.schedule'] = $app->share(function($c) use ($aConfig) {
            
            $oScheduleRepo = $c['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleEntity'); 
            $oScheduleRepo->setTableMap($aConfig['tablenames']);
    
            return $oScheduleRepo;
        });
  
    }

    public function boot(Application $app)
    {
 
    }
}
/* End of Class */

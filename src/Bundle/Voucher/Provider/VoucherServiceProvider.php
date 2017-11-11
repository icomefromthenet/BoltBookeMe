<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\CustomVoucherGenerator;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\CustomVoucherContainer;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\VoucherNumbers;

/**
 * Bootstrap The Voucher Generator 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class VoucherServiceProvider implements ServiceProviderInterface
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
        
        /**
         * Load our customer voucher service container
         * 
         * @return CustomVoucherContainer
         */ 
        $app['bm.voucher.container'] = $app->share(function($c) use ($aConfig) {
            
            $oDatabase      = $c['db'];
            $oEvent         = $c['dispatcher'];
            $oLogger        = $c['logger.system']; 
            $oGatewayProxy  = $c['bm.tablegateway.proxycollection'];
        
            $oNow           = $c['bm.now'];
            $aTables        = $aConfig['tablenames']; 
            
            $oContainer =  new CustomVoucherContainer($oDatabase, $oEvent, $oLogger, $oGatewayProxy);
              
            $oContainer->boot($oNow, $aTables); 
             
            $oContainer['table.voucher_group'] = function ($c) use ($aTables) {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['bm_voucher_group']);
            };
            
            $oContainer['table.voucher_type'] = function ($c) use ($aTables) {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['bm_voucher_type']);
        
            };
            
            $oContainer['table.voucher_instance'] = function ($c) use ($aTables) {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['bm_voucher_instance']);
        
            };
            
            $oContainer['table.voucher_rule'] = function ($c) use ($aTables) {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['bm_voucher_gen_rule']);
            };
            
              
             return $oContainer;
            
        });
        
        
        /**
         * Load custom voucher generator.
         *  
         * @return CustomVoucherGenerator
         */ 
        $app['bm.voucher.generator'] =  function($c){
            $oContainer =  $c['bm.voucher.container'];
            
            return new CustomVoucherGenerator($oContainer);
            
        };
        
        
        
        /**
         * Load the voucher service which API to generate voucher numbers
         * for the supported journals.
         * 
         * @return VoucherNumbers
         */ 
        $app['bm.voucher.service'] = $app->share(function($c) use ($aConfig){
            
            /** @var DateTime **/
            $oNow           = $c['bm.now'];
            
            $oVoucherNumbers = new VoucherNumbers();
            
            /** @var CustomVoucherContainer **/
            $oContainer =  $c['bm.voucher.container'];
             
            // Load the Types in Bulk
            /** @var IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherTypeGateway **/
            $oTypeGateway = $oContainer->getGateway(CustomVoucherContainer::DB_TABLE_VOUCHER_TYPE);
                             
            $aTypes =  $oTypeGateway->selectQuery()->start()->filterCurrent($oNow)->end()->find(); 
        
            foreach($aTypes as $oVoucherType) {
                /** @var CustomVoucherGenerator **/
                $oVoucherGenerator = $c['bm.voucher.generator'];
                
                $oVoucherGenerator->setVoucherType($oVoucherType);
                
                $oVoucherNumbers->registerVoucherGenerator($oVoucherType->getSlug(),$oVoucherGenerator);
                
            }
            
            return $oVoucherNumbers;
            
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
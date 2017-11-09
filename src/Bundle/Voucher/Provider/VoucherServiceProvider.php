<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\CustomVoucherContainer;
use IComeFromTheNet\VoucherNum\VoucherGenerator;

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
        
        
        $app['bm.voucher.generator'] = function($c){
            $oContainer =  $c['bm.voucher.container'];
            
            return new VoucherGenerator($oContainer);
            
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
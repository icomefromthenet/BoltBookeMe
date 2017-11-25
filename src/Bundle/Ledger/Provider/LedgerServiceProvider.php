<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\CustomLedgerContainer;
use IComeFromTheNet\IComeFromTheNet\GeneralLedger\TransactionBuilder;

/**
 * Bootstrap The Ledger Generator 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class LedgerServiceProvider implements ServiceProviderInterface
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
        
       
        $app['bm.ledger.container'] = $app->share(function($c) use ($aConfig) {
            
            $oDatabase      = $c['db'];
            $oEvent         = $c['dispatcher'];
            $oLogger        = $c['logger.system']; 
            $oGatewayProxy  = $c['bm.tablegateway.proxycollection'];
        
            $oNow           = $c['bm.now'];
            $aTables        = $aConfig['tablenames']; 
            
            $oContainer =  new CustomLedgerContainer($oEvent, $oDatabase, $oLogger, $oGatewayProxy);
              
            $oContainer->boot($aTables); 
         
            
            $oContainer['ledger_table_account'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_account']);
            };
        
            $oContainer['ledger_table_group'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_account_group']);
            };
        
            $oContainer['ledger_table_org'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_org_unit']);
            };
            
            $oContainer['ledger_table_user'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_user']); 
            };
            
            $oContainer['ledger_table_journal'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_journal_type']);
            };
            
            $oContainer['ledger_table_transaction'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_transaction']);
            };
            
            $oContainer['ledger_table_entry'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_entry']);
            };
            
            $oContainer['ledger_table_agg_daily'] = function($c)  {
               return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_daily']);
            };
            
            $oContainer['ledger_table_agg_user'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_daily_user']);
            };
            
            $oContainer['ledger_table_agg_org'] = function($c)  {
                return $c->getGatewayProxyCollection()->getSchema()->getTable($aTables['ledger_daily_org']);
            };   
            
            return $oContainer;
        });
        
        
        
        $app['bm.ledger.transaction.sales'] = function($c) {
          
          $oContainer = $c['bm.ledger.container'];  
        
          $oTransactionBuilder = new TransactionBuilder($oContainer);
          
          //$oTransactionBuilder->set
            
            
            return $oTransactionBuilder;
        };
       
        $app['bm.ledger.transaction.discounts'] = function($c) {
             $oContainer = $c['bm.ledger.container'];  
        
          $oTransactionBuilder = new TransactionBuilder($oContainer);
          
          //$oTransactionBuilder->set
            
            
            return $oTransactionBuilder;
         };
         
         $app['bm.ledger.transaction.general'] = function($c) {
             $oContainer = $c['bm.ledger.container'];  
        
          $oTransactionBuilder = new TransactionBuilder($oContainer);
          
          //$oTransactionBuilder->set
            
            
            return $oTransactionBuilder;
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
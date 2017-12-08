<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;

use Bolt\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalGeneral;
use Bolt\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalPayment;
use Bolt\IComeFromTheNet\BookMe\Bundle\Transaction\Model\JournalSale;


/**
 * Bootstrap The Transactions
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class TransactionServiceProvider implements ServiceProviderInterface
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
        
        
        
        $app['bm.transaction.journal.sale'] = function($c) {
          
          $oContainer = $c['bm.ledger.container'];  
        
          $oTransactionBuilder = new JournalSale($oContainer);
          
          return $oTransactionBuilder;
          
        };
       
        $app['bm.transaction.journal.payment'] = function($c) {
             $oContainer = $c['bm.ledger.container'];  
        
            $oTransactionBuilder = new JournalPayment($oContainer);
            
            return $oTransactionBuilder;
         };
         
         $app['bm.transaction.journal.general'] = function($c) {
            $oContainer = $c['bm.ledger.container'];  
        
            $oTransactionBuilder = new JournalGeneral($oContainer);
            
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
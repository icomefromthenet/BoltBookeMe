<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuGroup;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuException;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuBuilder;

/**
 * Bootstrap The Transaction Bundles Extendable menus.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class TransactionMenuProvider implements ServiceProviderInterface
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
        
        
        $app['bm.transactionm.menu'] = $app->share(
            $app->extend(
                'bm.menu.home',
                function ($oMenuBilder) {
                 
                 
                   $oMenuSetupGroup  = new MenuGroup('Sales and Payments', 300, 'bm-menu_linklistheading-setup');
                   
                   $oSetupGroupItemA = new MenuItem('Payments Report','View Recent Payments', 'bookme-transaction-report-payments', 'bla.png', 10, []);
                   $oSetupGroupItemB = new MenuItem('Sales Report','View Recent Sales', 'bookme-transaction-report-sales', 'bla.png', 20, []);

                    $oMenuSetupGroup->addMenuItem($oSetupGroupItemA);
                    $oMenuSetupGroup->addMenuItem($oSetupGroupItemB);
                
            
                    $oMenuBuilder->addMenuGroup($oMenuSetupGroup);
                 
                  
                    return $oMenuBilder;
                }
            )
        );
        
        

    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        
          
        
    }
}
/* End of Service Provider */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuGroup;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuException;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuBuilder;

/**
 * Bootstrap The Apps Extendable menus.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class MenuProvider implements ServiceProviderInterface
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
        
        
        $app['bm.menu.home'] = $app->share(function($c) use ($aConfig) {
             
        
            $oMenuGroupOne  = new MenuGroup('Group 1', 1);
            $oMenuGroupTwo  = new MenuGroup('Group 2', 2);
    
            $oGroupOneItemA = new MenuItem('Group One Menu Item A','Example Menu Item', 'bookme-rule-new-one', 'bla.png', 2, []);
            $oGroupOneItemB = new MenuItem('Menu One Item B','Example Menu Item', 'bookme-rule-new-one', 'bla.png', 1, []);
            
            $oGroupTwoItemA = new MenuItem('Group Two Menu Item A','Example Menu Item', 'bookme-rule-new-one', 'bla.png', 2, []);
            $oGroupTwoItemB = new MenuItem('Group Two Menu Item B','Example Menu Item', 'bookme-rule-new-one', 'bla.png', 1, []);
            
            $oMenuGroupOne->addMenuItem($oGroupOneItemA);
            $oMenuGroupOne->addMenuItem($oGroupOneItemB);
            
            $oMenuGroupTwo->addMenuItem($oGroupTwoItemA);
            $oMenuGroupTwo->addMenuItem($oGroupTwoItemB);
            
           
            $oMenuBuilder = new MenuBuilder();
            
            $oMenuBuilder->addMenuGroup($oMenuGroupOne);
            $oMenuBuilder->addMenuGroup($oMenuGroupTwo);
        
            
            return $oMenuBuilder;
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
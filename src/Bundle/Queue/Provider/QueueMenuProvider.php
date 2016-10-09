<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;

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
class QueueMenuProvider implements ServiceProviderInterface
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
        
        
         $app['bm.menu.home'] = $app->share(
            $app->extend(
                'bm.menu.home',
                function ($oMenuBilder) {
                    foreach($oMenuBilder->getMenuGroups() as $oMenuGroup) {
                        if(strtolower($oMenuGroup->getGroupName()) == 'schedule rules') {
                            $oMenuItem = new MenuItem('View Schedule Build Queue','View the schedules in queue waiting for a rebuild after rule change', 'bookme-queue-page', 'bla.png', 50, []);
                            $oMenuGroup->addMenuItem($oMenuItem);
                            break;
                        }
                    }
                  
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
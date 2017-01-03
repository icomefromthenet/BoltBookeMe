<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuGroup;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuException;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\Custom\MemberActionMenu;
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
             
            $oMenuBuilder = new MenuBuilder();
            
            // -------------------------------------------------------------
            //Setup 
        
            $oMenuSetupGroup  = new MenuGroup('Setup Book Me', 100, 'bm-menu_linklistheading-setup');
            $oSetupGroupItemA = new MenuItem('Calendar Setup','Create new Calendar Years', 'bookme-setup-calendar', 'bla.png', 10, []);
            $oSetupGroupItemB = new MenuItem('Timeslot Setup','Manage Timeslots used in schedules', 'bookme-setup-timeslot', 'bla.png', 20, []);

            $oMenuSetupGroup->addMenuItem($oSetupGroupItemA);
            $oMenuSetupGroup->addMenuItem($oSetupGroupItemB);
        
    
            $oMenuBuilder->addMenuGroup($oMenuSetupGroup);
      
    
    
            //----------------------------------------------------------------
            // Rules
        
            $oMenuRulesGroup  = new MenuGroup('Schedule Rules', 10, 'bm-menu_linklistheading-rule');
            $oRulesGroupItemA = new MenuItem('Create Availability Rule','Rules that define when your available for bookings', 'bookme-rule-new-one', 'bla.png', 10, ['sRuleTypeCode' =>'workday']);
            $oRulesGroupItemB = new MenuItem('Create Holiday Rule','Rule that define when on holiday', 'bookme-rule-new-one', 'bla.png', 20, ['sRuleTypeCode' =>'holiday']);
            $oRulesGroupItemC = new MenuItem('Create Break Rule','Rule that define when your on break during day', 'bookme-rule-new-one', 'bla.png', 30, ['sRuleTypeCode' =>'break']);
            $oRulesGroupItemD = new MenuItem('Create Overtime Rule','Rule that define when ready for extra work', 'bookme-rule-new-one', 'bla.png', 40, ['sRuleTypeCode' =>'overtime']);
            $oRulesGroupItemE = new MenuItem('Search Schedule Rules','Find a Rule upcoming and past ', 'bookme-rule-search', 'bla.png', 50, []);
            $oRulesGroupItemF = new MenuItem('List Schedule Rules','List rules that intersect with current calendar week ', 'bookme-rule-list', 'bla.png', 60, []);
    
         
            $oMenuRulesGroup->addMenuItem($oRulesGroupItemA);
            $oMenuRulesGroup->addMenuItem($oRulesGroupItemB);
            $oMenuRulesGroup->addMenuItem($oRulesGroupItemC);
            $oMenuRulesGroup->addMenuItem($oRulesGroupItemD);
            $oMenuRulesGroup->addMenuItem($oRulesGroupItemE);
            $oMenuRulesGroup->addMenuItem($oRulesGroupItemF);
         
         
            $oMenuBuilder->addMenuGroup($oMenuRulesGroup);
        
            
            
            //----------------------------------------------------------------
            // Schedule
           
            $oScheduleGroup   = new MenuGroup('Schedules',20, 'bm-menu_linklistheading-rule');
            $oListMembersItem = new MenuItem('List Members','View workers who have schedules', 'bookme-worker-list', 'bla.png', 10, []);
          
        
    
        
            $oScheduleGroup->addMenuItem($oListMembersItem);
    
            $oMenuBuilder->addMenuGroup($oScheduleGroup);
      
            //------------------------------------------------------------------
            // Done forget to validate 
            
            $oMenuBuilder->validate();
            
            return $oMenuBuilder;
        });
        
        
        $app['bm.menu.member'] = $app->share(function($c) use ($aConfig) {
            
            $oMenuBuilder = new MenuBuilder();
                
                
            $oMemberGroup  = new MenuGroup('Member Options', 100, 'bm-menu_linklistheading-setup');
            $oMemberDetails = new MenuItem('Member Basic Details','Change Member Basic Details', 'bookme-worker-view-basic', 'bla.png', 10, []);  
            
    
            // -----------------------------------------------------------------
            // Do Links
         
         
            $oMemberGroup->addMenuItem($oMemberDetails);
            $oMenuBuilder->addMenuGroup($oMemberGroup);
            
            //------------------------------------------------------------------
            // Done forget to validate 
            
            $oMenuBuilder->validate();
            
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
<?php

namespace Bolt\Extension\IComeFromTheNet\BookMe;

use DateTime;
use Silex\Application;
use Bolt\Asset\File\JavaScript;
use Bolt\Asset\File\Stylesheet;
use Bolt\Controller\Zone;
use Bolt\Events\StorageEvent;
use Bolt\Events\StorageEvents;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Menu\MenuEntry;
use Silex\ControllerCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Bolt\Extension\IComeFromTheNet\BookMe\Listener\StorageEventListener;

use Bolt\Extension\IComeFromTheNet\BookMe\Schema\CalendarTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\CalendarWeekTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\CalendarMonthTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\CalendarQuarterTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\CalendarYearTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\InitTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\TimeslotTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\TimeslotDayTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\TimeslotYearTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\ScheduleMembershipTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\ScheduleTeamTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\ScheduleTeamMemberTable;

// Load this extension composer dep autoloader
// since bolt does not download packagist repo when does a merge
// this extension must manage its own dependecies
require (__DIR__.'/../thirdparty/vendor/autoload.php');


/**
 * ExtensionName extension class.
 *
 * @author Your Name <you@example.com>
 */
class BookMeExtension extends SimpleExtension
{
    
    use DatabaseSchemaTrait;
    
    
    //--------------------------------------------------------------------------
    # Properties
    
    /**
     * Fetch the processing date used i.e. NOW()
     * 
     * @return DateTime
     */ 
    protected function getNow()
    {
        return new DateTime();
    }

    /**
     * Return the database adapter
     * 
     * @return Bolt\Storage\Database\Connection
     */ 
    protected function getDatabase()
    {
        $oContainer = $this->getContainer();
        
        return $oContainer['db'];
    }
    
   
    
    

   //---------------------------------------------------------------------------
   # Bolt Types
    
    
    
    /**
     * {@inheritdoc}
     */
    public function registerFields()
    {
        /*
         * Custom Field Types:
         * You are not limited to the field types that are provided by Bolt.
         * It's really easy to create your own.
         *
         * This example is just a simple text field to show you
         * how to store and retrieve data.
         *
         * See also the documentation page for more information and a more complex example.
         * https://docs.bolt.cm/extensions/customfields
         */

        return [
           
        ];
    }
    
    
    //--------------------------------------------------------------------------
    # Services and Database
    
    /**
     * Return Service Providers to load
     * 
     */ 
    public function getServiceProviders()
    {
       
        $parentProviders = parent::getServiceProviders();
        $localProviders = [
            new Provider\CommandBusProvider($this->getConfig()),
            new Provider\CustomValidationProvider($this->getConfig()),
        ];

        return array_merge($parentProviders,$localProviders);
    }
    
     
    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $this->extendDatabaseSchemaServices();
        
        
        return $app;
    }
    
    
     /**
     * {@inheritdoc}
     */
    protected function registerExtensionTables()
    {
        return [
            'ints'                  => InitTable::class,
            'bm_calendar'           => CalendarTable::class,
            'bm_calendar_weeks'     => CalendarWeekTable::class,
            'bm_calendar_months'    => CalendarMonthTable::class,
            'bm_calendar_quarters'  => CalendarQuarterTable::class,
            'bm_calendar_years'     => CalendarYearTable::class,
            
            'bm_timeslot'           => TimeslotTable::class,
            'bm_timeslot_day'       => TimeslotDayTable::class,
            'bm_timeslot_year'      => TimeslotYearTable::class,
            'bm_schedule_membership'=> ScheduleMembershipTable::class,
            'bm_schedule_team'         => ScheduleTeamTable::class,
            'bm_schedule_team_members' => ScheduleTeamMemberTable::class,
            
        ];
    }
    
    
    

    //--------------------------------------------------------------------------
    # Assets
   
    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {
        return [
            // Web assets that will be loaded in the frontend
          
            // Web assets that will be loaded in the backend
          
          
        ];
    }
    
    //--------------------------------------------------------------------------
    # Twig Extensions

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return ['templates'];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
            'my_twig_function' => 'myTwigFunction',
        ];
    }

    /**
     * The callback function when {{ my_twig_function() }} is used in a template.
     *
     * @return string
     */
    public function myTwigFunction()
    {
        $context = [
            'something' => mt_rand(),
        ];

        return $this->renderTemplate('extension.twig', $context);
    }
    
    //--------------------------------------------------------------------------
    # Menu Entires and Routes

    /**
     * {@inheritdoc}
     *
     * Extending the backend menu:
     *
     * You can provide new Backend sites with their own menu option and template.
     *
     * Here we will add a new route to the system and register the menu option in the backend.
     *
     * You'll find the new menu option under "Extras".
     */
    protected function registerMenuEntries()
    {
        /*
         * Define a menu entry object and register it:
         *   - Route http://example.com/bolt/extend/my-custom-backend-page-route
         *   - Menu label 'MyExtension Admin'
         *   - Menu icon a Font Awesome small child
         *   - Required Bolt permissions 'settings'
         */
        $adminMenuEntry = (new MenuEntry('bookme-calendar-admin', 'bookme/setup'))
            ->setLabel('Book Me Calendar Setup')
            ->setIcon('fa:child')
            ->setPermission('settings')
        ;

        return [$adminMenuEntry];
    }

    /**
     * {@inheritdoc}
     *
     * Mount the ExampleController class to all routes that match '/example/url/*'
     *
     * To see specific bindings between route and controller method see 'connect()'
     * function in the ExampleController class.
     */
    protected function registerFrontendControllers()
    {
       
    }

   
    /**
     * {@inheritdoc}
     */
    protected function registerBackendControllers()
    {
        $app = $this->getContainer();
        $config = $this->getConfig();
      
        return [
            'extend/bookme/setup' => new Controller\SetupController($config,$this->getNow(),$this->getDatabase()),
        ];
        
        
    }


    //--------------------------------------------------------------------------
    # Config
    
     /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig()
    {
        return [
            'tablenames' => [
                 'bm_ints'              => 'bolt_ints'   
                ,'bm_calendar'          => 'bolt_bm_calendar'    
                ,'bm_calendar_weeks'    => 'bolt_bm_calendar_weeks'      
                ,'bm_calendar_months'   => 'bolt_bm_calendar_months'  
                ,'bm_calendar_quarters' => 'bolt_bm_calendar_quarters'  
                ,'bm_calendar_years'    => 'bolt_bm_calendar_years'
                
                ,'bm_timeslot'          => 'bolt_bm_timeslot'
                ,'bm_timeslot_day'      => 'bolt_bm_timeslot_day'
                ,'bm_timeslot_year'       => 'bolt_bm_timeslot_year'
                
                ,'bm_schedule_membership'  => 'bolt_bm_schedule_membership'
                ,'bm_schedule_team'        => 'bolt_bm_schedule_team'
                ,'bm_schedule_team_members' => 'bolt_bm_schedule_team_members'
            ]
            
            
        ];
    }
   

}
/* End of Extension */

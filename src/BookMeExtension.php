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
use Bolt\Extension\StorageTrait;
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
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\ScheduleTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\ScheduleSlotTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\ScheduleTeamMemberTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\BookingTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\BookingConflictTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\RuleTypeTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\RuleTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\RuleSeriesTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\RuleScheduleTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\CustomerTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\ActivityTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\AppointmentStatusTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Schema\AppointmentTable;



use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleRepository;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeRepository;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleRepository;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MemberEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MemberRepository;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamRepository;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerRepository;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearRepository;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotRepository;



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
    use StorageTrait;
    
   
    //--------------------------------------------------------------------------
    # Properties
  
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
        # Process core    
        $aConfig = $this->getConfig();
       
        $parentProviders = parent::getServiceProviders();
        $localProviders = [
            new Provider\StorageExtensionsProvider($aConfig),
            new Provider\CommandBusProvider($aConfig),
            new Provider\CronParseProvider($aConfig),
            new Provider\CustomValidationProvider($aConfig),
            new Provider\ExtrasProvider($aConfig),
            new Provider\FormProvider($aConfig),
            new Provider\SearchQueryProvider($aConfig),
            new Provider\MenuProvider($aConfig),
            new Provider\DataTableProvider($aConfig),
        ];
        
        $aBunldes = [];
        
        # Process bundles
        foreach($aConfig['bundle'] as $sBundle => $bUseBundle) {
            if($bUseBundle) {
                $sClass =  'Bolt\\Extension\\IComeFromTheNet\\BookMe\\Bundle\\'.$sBundle.'\\'.$sBundle.'Bundle';
                $oClass =  new $sClass($aConfig);
                
                $oClass->setBaseDirectory($this->getBaseDirectory());
                $oClass->setWebDirectory($this->getWebDirectory());
                
                $aBunldes[] =  $oClass;
            }
        }
        
    
        return array_merge($parentProviders,$localProviders,$aBunldes);
    }
    
     
    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $this->extendDatabaseSchemaServices();
        $this->extendRepositoryMapping();
 
        
        return $app;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function registerRepositoryMappings()
    {
        return [
            'bm_rule'               => [RuleEntity::class => RuleRepository::class],
            'bm_rule_type'          => [RuleTypeEntity::class => RuleTypeRepository::class],
            'bm_schedule'           => [ScheduleEntity::class => ScheduleRepository::class],
            'bm_schedule_membership'=> [MemberEntity::class => MemberRepository::class],
            'bm_customer'           => [CustomerEntity::class => CustomerRepository::class],
            'bm_calendar_years'     => [CalendarYearEntity::class => CalendarYearRepository::class],
            'bm_timeslot'           => [TimeslotEntity::class => TimeslotRepository::class],
            'bm_schedule_team'      => [TeamEntity::class => TeamRepository::class],
        ];
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
            'bm_schedule_team'      => ScheduleTeamTable::class,
            'bm_schedule'           => ScheduleTable::class,  
            'bm_schedule_slot'      => ScheduleSlotTable::class,
            'bm_schedule_team_members' => ScheduleTeamMemberTable::class,
            
            'bm_booking'            => BookingTable::class,
            'bm_booking_conflict'   => BookingConflictTable::class,
            
            'bm_rule_type'          => RuleTypeTable::class,
            'bm_rule'               => RuleTable::class,
            'bm_rule_series'        => RuleSeriesTable::class,
            'bm_rule_schedule'      => RuleScheduleTable::class,
            
            'bm_customer'           => CustomerTable::class,
            'bm_activity'           => ActivityTable::class,
            'bm_appointment_status' => AppointmentStatusTable::class,
            'bm_appointment'        => AppointmentTable::class,
            
        ];
    }
    
    
    

    //--------------------------------------------------------------------------
    # Assets
   
    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {
        $oAppCssAsset = new Stylesheet();
        $oAppCssAsset->setFileName('dist/css/bookme.css')
            ->setLate(false)
            ->setPriority(99)
            ->setZone(Zone::BACKEND);
        
        $oVendorCSSAsset = new Stylesheet();
        $oVendorCSSAsset->setFileName('dist/vendor/css/vendor.css')
            ->setLate(false)
            ->setPriority(98)
            ->setZone(Zone::BACKEND);
        
        $oAppJsAsset = new JavaScript();
        $oAppJsAsset->setFileName('dist/js/bookme.js')
            ->setLate(false)
            ->setPriority(50)
            ->setZone(Zone::BACKEND);

        
        $oVendorJSAsset = new JavaScript();
        $oVendorJSAsset->setFileName('dist/vendor/js/vendor.js')
            ->setLate(false)
            ->setPriority(45)
            ->setZone(Zone::BACKEND);

      
        
        return [
            // Web assets that will be loaded in the frontend
        
          
            // Web assets that will be loaded in the backend
            $oAppCssAsset,
            $oVendorCSSAsset,
            $oAppJsAsset,
            $oVendorJSAsset
        ];
    }
    
    //--------------------------------------------------------------------------
    # Twig Extensions

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return ['/templates/' => ['namespace' => 'BookMe']];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
            'convertSlotTime'   => 'convertSlotTime',
            'bookMeCSSAsset'    => 'bookMeCSSAsset',
            'bookMeScriptAsset' => 'bookMeScriptAsset',
        ];
    }

    /**
     * The callback function when {{ my_twig_function() }} is used in a template.
     *
     * @return string
     */
    public function convertSlotTime($iTimeslot,$bOpening)
    {
        $oDate = new DateTime('now');
        $sFormat = 'H:i:s';
        
        
        
        $sValue =  $oDate->setTime(0,0,0)->modify('+'.$iTimeslot. ' minutes')->format($sFormat);
        
        if(true == $bOpening &&  $sValue == '00:00:00') {
            $sValue = 'Start of Day';
            
        } 
        
        if(false == $bOpening  && $sValue == '00:00:00') {
            $sValue = 'End of Day';
        }
        
        return $sValue;
    }
    
    /**
     * Render a url for the current extension web assets
     * 
     *  This does what AssetTrait::normalizeAsset
     * 
     * @return string he path be used as url after the domain
     */ 
    public function bookMeCSSAsset($path)
    {
       
        $app = $this->getContainer();
       
        $asset = new Stylesheet();
        $asset->setFileName($path)
            ->setLate(false)
            ->setZone(Zone::BACKEND);
       
       
        // Any external resource does not need further normalisation
        if (parse_url($path, PHP_URL_HOST) !== null) {
            return $path;
        }
        
        $file = $this->getWebDirectory()->getFile($asset->getPath());
        
        
        if ($file->exists()) {
            $asset->setPackageName('extensions')->setPath($file->getPath());
        } else {
            throw new BookMeException('Asset :: '.$file->getPath(). ' Does not Exists on filesystem');
        }
        
        
        return $app['asset.packages']->getUrl($asset->getPath(),'extensions');
    
    }
    
     /**
     * Render a url for the current extension web assets
     * 
     *  This does what AssetTrait::normalizeAsset
     * 
     * @return string he path be used as url after the domain
     */ 
    public function bookMeScriptAsset($path)
    {
        $app = $this->getContainer();
        
        $asset = new JavaScript();
        $asset->setFileName($path)
            ->setLate(false)
            ->setZone(Zone::BACKEND);
       
       
        // Any external resource does not need further normalisation
        if (parse_url($path, PHP_URL_HOST) !== null) {
            return $path;
        }

        $file = $this->getWebDirectory()->getFile($asset->getPath());
        
        
        if ($file->exists()) {
            $asset->setPackageName('extensions')->setPath($file->getPath());
        } else {
            throw new BookMeException('Asset :: '.$file->getPath(). ' Does not Exists on filesystem');
        }
        
        return $app['asset.packages']->getUrl($asset->getPath(),'extensions');

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
        $adminMenuEntry = (new MenuEntry('bookme-home', 'bookme/home'))
            ->setLabel('Book Me Home')
            ->setIcon('fa:child')
            ->setPermission('settings');

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
       return [];
    }

   
    /**
     * {@inheritdoc}
     */
    protected function registerBackendControllers()
    {
        $app = $this->getContainer();
        $config = $this->getConfig();
      
        return [
            'extend/bookme/home'          => new Controller\HomeController($config, $app, $this),
            'extend/bookme/setup'         => new Controller\SetupController($config, $app, $this),
            'extend/bookme/worker'        => new Controller\WorkerController($config, $app, $this),
            'extend/bookme/appointment'   => new Controller\AppointmentController($config, $app, $this),
            'extend/bookme/schedule'      => new Controller\ScheduleController($config, $app, $this),
            'extend/bookme/rule'          => new Controller\RuleController($config, $app, $this),
        ];
        
        
    }


    //--------------------------------------------------------------------------
    # Config
    
     /**
     * {@inheritdoc}
     */
    public function getDefaultConfig()
    {
        return [
            'apptnumber' => [
                 'suffix'       => '#'    
                ,'prefix'       => 'A'    
                ,'starting'     => 1000
            ]
            ,'tablenames' => [
                 'bm_ints'              => 'bolt_ints'   
                ,'bm_calendar'          => 'bolt_bm_calendar'    
                ,'bm_calendar_weeks'    => 'bolt_bm_calendar_weeks'      
                ,'bm_calendar_months'   => 'bolt_bm_calendar_months'  
                ,'bm_calendar_quarters' => 'bolt_bm_calendar_quarters'  
                ,'bm_calendar_years'    => 'bolt_bm_calendar_years'
                
                ,'bm_timeslot'          => 'bolt_bm_timeslot'
                ,'bm_timeslot_day'      => 'bolt_bm_timeslot_day'
                ,'bm_timeslot_year'      => 'bolt_bm_timeslot_year'
                
                ,'bm_schedule_membership' => 'bolt_bm_schedule_membership'
                ,'bm_schedule_team'       => 'bolt_bm_schedule_team'
                ,'bm_schedule'            => 'bolt_bm_schedule'
                ,'bm_schedule_slot'       => 'bolt_bm_schedule_slot'
                ,'bm_schedule_team_members' => 'bolt_bm_schedule_team_members'
               
               
                ,'bm_booking'             => 'bolt_bm_booking'
                ,'bm_booking_conflict'    => 'bolt_bm_booking_conflict'
                
                ,'bm_rule_type'           => 'bolt_bm_rule_type'
                ,'bm_rule'                => 'bolt_bm_rule'
                ,'bm_rule_series'         => 'bolt_bm_rule_series'
                ,'bm_rule_schedule'       => 'bolt_bm_rule_schedule'
                
                ,'bm_tmp_rule_series'     => 'bm_tmp_rule_series'
                
                ,'bm_customer'            => 'bolt_bm_customer'
                ,'bm_activity'            => 'bolt_bm_activity'
                ,'bm_appointment_status'  => 'bolt_bm_appointment_status'
                ,'bm_appointment'         => 'bolt_bm_appointment'
                
                ,'bm_queue'               => 'bolt_bm_queue'
                ,'bm_queue_monitor'       => 'bolt_bm_queue_monitor'
                ,'bm_queue_transition'    => 'bolt_bm_queue_transition'

            ]
            ,'leadtime' => 'PT1D'
            
            ,'bundle' => [
                'AuditTrail' => true,
                'Queue'      => true,   
                'Rollover'   => true,
                'HolidayRule' => true,
            ]
            ,'queue' => [
                'worker' => [
                        'jobs_process'      => 300,
                        'mean_runtime'      => (60*60*1),
                        'cron_script'       => '*/5 * * * *',
                        'job_lock_timeout'  => (60*60*4),
                        'worker_name'       => 'scheduleprocess',
                        'purge_days'        => 30,
                ],
                'queue' => [
                        'mean_service_time' => (60*60*1),
                        'max_retry'         => 3,
                        'retry_timer'       => (60*60*1)
                ],
                
            ]
        ];
    }
   

}
/* End of Extension */

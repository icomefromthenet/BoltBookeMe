<?php

namespace Bolt\Extension\IComeFromTheNet\BookMe;

use DateTime;
use Bolt\Application;
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


/**
 * ExtensionName extension class.
 *
 * @author Your Name <you@example.com>
 */
class BookMeExtension extends SimpleExtension
{
    
    use DatabaseSchemaTrait;
    
    
    
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
        ];
    }
    
    
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
            new Field\ExampleField(),
        ];
    }
    
    /**
     * Return Service Providers to load
     * 
     */ 
    public function getServiceProviders()
    {
        $parentProviders = parent::getServiceProviders();
        $localProviders = [
            new Provider\CommandBusProvider($this->getConfig()),
        ];

        return $parentProviders + $localProviders;
    }
    

   
    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {
        return [
            // Web assets that will be loaded in the frontend
            new Stylesheet('extension.css'),
            new JavaScript('extension.js'),
            // Web assets that will be loaded in the backend
            (new Stylesheet('clippy.js/clippy.css'))->setZone(Zone::BACKEND),
            (new JavaScript('clippy.js/clippy.min.js'))->setZone(Zone::BACKEND),
        ];
    }

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
        $adminMenuEntry = (new MenuEntry('bookme-calendar-admin', 'bookme/calendar/admin'))
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
            'extend/bookme/calendar' => new Controller\CalendarAdminController($config,$this->getNow(),$this->getDatabase()),
        ];
        
        
    }


}
/* End of Extension */

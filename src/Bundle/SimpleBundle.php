<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle;

use Pimple as Container;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Bolt\Helpers\Str;
use Bolt\Filesystem\Handler\DirectoryInterface;
use Bolt\Extension\ExtensionInterface;
use Bolt\Events\ControllerEvents;
use Bolt\Extension\AssetTrait;
use Bolt\Extension\ConfigTrait;
use Bolt\Extension\ControllerTrait;
use Bolt\Extension\ControllerMountTrait;
use Bolt\Extension\MenuTrait;
use Bolt\Extension\NutTrait;
use Bolt\Extension\TwigTrait;
use Bolt\Extension\StorageTrait;

/**
 * These bundles are extensions that are loaded by the BookeMe Core.
 * 
 * I had to duplicate the extension code from Bolt so could use differnt name 
 * method.
 * 
 * Bolt Extension would use a service provider called ExtensionNameExtension these
 * bundles use name like BundleNameBundle.
 *
 * 
 * A Bundle should using only the public API add extra functionality. As they are 
 * bolt extension they can load field,template,database tables and service providers.
 * 
 * As these bundles are loaded after the core they can use container extensions to
 * add new functions to:
 * 
 * 1. Internal Menus.
 * 2. Form Builder used for search queries.
 * 3. Filters and Directivies to build a search queries.
 * 3. Extra Columns to client DataTables.
 * 4. Add Extra decerators to existing commands in our bus.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class SimpleBundle implements ExtensionInterface, ServiceProviderInterface, EventSubscriberInterface
{
    
    use AssetTrait;
    use ConfigTrait;
    use ControllerTrait;
    use ControllerMountTrait;
    use MenuTrait;
    use NutTrait;
    use TwigTrait;
   
    
     /** @var Container */
    protected $container;
    /** @var DirectoryInterface|null */
    protected $baseDirectory;
    /** @var DirectoryInterface|null */
    protected $webDirectory;
    /** @var string */
    protected $name;
    /** @var string */
    protected $vendor;
    /** @var string */
    protected $namespace;
    /** @var array */
    protected $aParentConfig;


    public function __construct(array $aConfig)
    {
        $this->aParentConfig = $aConfig;
    }


    /**
     * {@inheritdoc}
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseDirectory(DirectoryInterface $directory)
    {
        $this->baseDirectory = $directory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseDirectory()
    {
        if ($this->baseDirectory === null) {
            throw new \LogicException('Extension was not added with a base directory');
        }

        return $this->baseDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function setWebDirectory(DirectoryInterface $directory)
    {
        $this->webDirectory = $directory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWebDirectory()
    {
        if ($this->webDirectory === null) {
            throw new \LogicException('Extension was not added with a web directory');
        }

        return $this->webDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getVendor() . '/' . $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        if ($this->name === null) {
            // Get name from class name without Extension suffix
            $parts = explode('\\', get_class($this));
            $name = array_pop($parts);
            $pos = strrpos($name, 'Bundle');
            if ($pos !== false) {
                $name = substr($name, 0, $pos);
            }
            // If class name is "Extension" use last part of namespace.
            if ($name === '') {
                $name = array_pop($parts);
            }

            $this->name = $name;
        }

        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getVendor()
    {
        if ($this->vendor === null) {
            $namespace = $this->getNamespace();
            $name = Str::replaceFirst('Bolt\\Extension\\', '', $namespace);
            $pos = strpos($name, '\\');
            $this->vendor = $pos === false ? $name : substr($name, 0, $pos);
        }

        return $this->vendor;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        if ($this->namespace === null) {
            $class = get_class($this);
            $this->namespace = substr($class, 0, strrpos($class, '\\'));
        }

        return $this->namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        return $this->getName();
    }

    /**
     * Return the container.
     *
     * Note: This is allows traits to access app without losing coding completion
     *
     * @return Container
     */
    protected function getContainer()
    {
        return $this->container;
    }
    
    
  

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $this->setContainer($app);
       
       
        $this->extendConfigService();
        $this->extendTwigService();
        $this->extendMenuService();
        $this->extendAssetServices();
        $this->extendNutService();
        

        $this->registerServices($app);
        
    }

    /**
     * Register additional services for the extension.
     *
     * Example:
     * <pre>
     *   $app['koala'] = $app->share(
     *       function ($app) {
     *           return new Koala($app['drop.bear']);
     *       }
     *   );
     * </pre>
     *
     * @param Application $app
     */
    protected function registerServices(Application $app)
    {
        
        foreach($this->getServiceProviders() as $oProvider) {
            $oProvider->register($app);        
        }
        
    }

    /**
     * Define events to listen to here.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    protected function subscribe(EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceProviders()
    {
        return [$this];
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        $this->container = $app;
        $this->container['dispatcher']->addSubscriber($this);
        $this->subscribe($this->container['dispatcher']);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ControllerEvents::MOUNT => [
                ['onMountRoutes', 0],
                ['onMountControllers', 0],
            ],
        ];
    }
    
    
    /**
     * Return the BookMe Extension config
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return $this->aParentConfig;
    }
    
    
    
}
/* End of Class */
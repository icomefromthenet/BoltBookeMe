<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order;

use DateTime;
use Silex\Application;
use Bolt\Events\CronEvents;
use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\StorageTrait;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\SimpleBundle;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Provider;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Controller;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Schema;



class OrderBundle extends SimpleBundle
{
    
    use DatabaseSchemaTrait;
    use StorageTrait;
    
    public function getServiceProviders() {
    
        $aConfig = $this->getConfig();

        $localProviders = [
             new Provider\OrderMenuProvider($aConfig),
             new Provider\OrderDataTableProvider($aConfig),
             new Provider\OrderFormProvider($aConfig),
             new Provider\OrderServiceProvider($aConfig),
             new Provider\OrderCommandBusProvider($aConfig),
             
        ];
    
        return $localProviders;
    }
    
    
    protected function registerRepositoryMappings()
    {
        return [
            'bm_order_appointment'  => [Model\OrderApptEntity::class       => Model\OrderApptRepository::class],
            'bm_order_package'      => [Model\OrderPackageEntity::class    => Model\OrderPackageRepository::class],
            'bm_order_coupon'       => [Model\OrderCouponEntity::class     => Model\OrderCouponRepository::class],
            'bm_order_surcharge'    => [Model\OrderSurchargeEntity::class  => Model\OrderSurchargeRepository::class],
        ];
    }
    
    
    protected function registerServices(Application $app)
    {
        $this->extendDatabaseSchemaServices();
        $this->extendRepositoryMapping();
       
        parent::registerServices($app);
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
        
        return ['/src/Bundle/Order/Resources/view' => ['namespace' => 'bmOrder']];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
            
        ];
    }
    
    //--------------------------------------------------------------------------
    # Database 
    
    
     /**
     * {@inheritdoc}
     */
    protected function registerExtensionTables()
    {
        
        return [
            'bm_order_appointment'           => Schema\OrderApptTable::class,
            'bm_order_coupon'                => Schema\OrderCouponTable::class,
            'bm_order_package'               => Schema\OrderPackageTable::class,
            'bm_order_surcharge'             => Schema\OrderSurchargeTable::class,
            'bm_order_appointment_coupon'    => Schema\OrderApptCouponTable::class,
            'bm_order_appointment_package'   => Schema\OrderApptPackageTable::class,
            'bm_order_appointment_surcharge' => Schema\OrderApptSurchargeTable::class,
        ];
    
        
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
        return [];
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
            'extend/bookme/order/order' =>  new Controller\OrderController($config,$app,$this),
                            
        ];
        
        
    }
    
   

    //--------------------------------------------------------------------------
}
/* End of Class */
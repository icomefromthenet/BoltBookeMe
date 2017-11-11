<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger;

use DateTime;
use Silex\Application;
use Bolt\Events\CronEvents;
use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\SimpleBundle;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Provider;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Controller;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;



class LedgerBundle extends SimpleBundle
{
    
    use DatabaseSchemaTrait;
    
    
    public function getServiceProviders() {
    
        $aConfig = $this->getConfig();

        $localProviders = [
             new Provider\LedgerMenuProvider($aConfig),
             new Provider\LedgerDataTableProvider($aConfig),
             new Provider\LedgerFormProvider($aConfig),
             new Provider\LedgerServiceProvider($aConfig),
             
        ];
    
        return $localProviders;
    }
    
    
    
    protected function registerServices(Application $app)
    {
         $this->extendDatabaseSchemaServices();
       
       
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
        
        return ['/src/Bundle/Ledger/Resources/view' => ['namespace' => 'bmLedger']];
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
           'bm_ledger_account'           => Schema\LedgerAccountTable::class,
           'bm_ledger_account_group'     => Schema\LedgerAccountGroupTable::class,
           'bm_ledger_org_unit'          => Schema\LedgerOrgGroupTable::class,
           'bm_ledger_user'              => Schema\LedgerUserTable::class,
           'bm_ledger_journal_type'      => Schema\LedgerJournalTypeTable::class,
           'bm_ledger_transaction'       => Schema\LedgerTransactionTable::class,
           'bm_ledger_entry'             => Schema\LedgerEntryTable::class,
           'bm_ledger_daily'             => Schema\LedgerDailyTable::class,
           
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
            'extend/bookme/ledger/ledgers' =>  new Controller\LedgerController($config,$app,$this),
                            
        ];
        
        
    }
    
   

    //--------------------------------------------------------------------------
}
/* End of Class */
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;


use Silex\Application;
use Silex\ServiceProviderInterface;

use League\Tactician\CommandBus;
use League\Tactician\Handler\Locator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\CommandEvents\EventMiddleware;
use League\Tactician\CommandEvents\Event\CommandHandled;

use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandHandled as CustomHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidatePropMiddleware;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ExceptionWrapperMiddleware;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\UnitOfWorkMiddleware;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\CalAddYearCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Handler\CalAddYearHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\SlotAddCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Handler\SlotAddHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\SlotToggleStatusCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Handler\SlotToggleStatusHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\RolloverTimeslotCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Handler\RolloverTimeslotHandler;

/**
 * Bootstrap the Command Bus used for booking operations.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class CommandBusProvider implements ServiceProviderInterface
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
      
        $app['bm.model.setup.handler.addyear'] = $app->share(function(Application $container) use ($aConfig){
            return new CalAddYearHandler($aConfig['tablenames'],$container['db']);
        });
        
        $app['bm.model.setup.handler.addslot'] = $app->share(function(Application $container) use ($aConfig){
            return new SlotAddHandler($aConfig['tablenames'],$container['db']);
        });
        
        $app['bm.model.setup.handler.toggleslot'] = $app->share(function(Application $container) use ($aConfig){
            return new SlotToggleStatusHandler($aConfig['tablenames'],$container['db']);
        });
        
        $app['bm.model.setup.handler.rolloverslot'] = $app->share(function(Application $container) use ($aConfig){
            return new RolloverTimeslotHandler($aConfig['tablenames'],$container['db']);
        });
      
      
       $app['bm.leagueeventhandler'] = function($c) {
            return new CustomHandler($c['dispatcher']);
        };
      
      
       $app['bm.commandBus'] = $app->share(function(Application $c) {
                
           # fetch database
           $oDatabase = $c['db'];
          
           
            $aLocatorMap = [
                CalAddYearCommand::class            => 'bm.model.setup.handler.addyear',
                SlotAddCommand::class               => 'bm.model.setup.handler.addslot',
                SlotToggleStatusCommand::class      => 'bm.model.setup.handler.toggleslot',
                RolloverTimeslotCommand::class      => 'bm.model.setup.handler.rolloverslot',
                
                
                
            ];
            
            
    
         
            // Create the Middleware that loads the commands
         
            $oCommandNamingExtractor = new ClassNameExtractor();
            $oCommandLoadingLocator  = new PimpleLocator($c, $aLocatorMap);
            $oCommandNameInflector   = new HandleInflector();
                
            $oCommandMiddleware      = new CommandHandlerMiddleware($oCommandNamingExtractor,$oCommandLoadingLocator,$oCommandNameInflector);
            
            // Create exrta Middleware 

            $oEventMiddleware       = new EventMiddleware();
            $oEventMiddleware->addListener(
            	'command.handled',
            	function (CommandHandled $event) use ($c) {
                	// custom handler withh convert this league event into symfony event
                	$oCustomEventHandler = $c['bm.leagueeventhandler']; 
                	$oCustomEventHandler->handle($event);
            	}
            );
            
            
            $oLockingMiddleware     = new LockingMiddleware();
            $oValdiationMiddleware  = new ValidatePropMiddleware();
            $oExceptionMiddleware   = new ExceptionWrapperMiddleware();
            $oUnitOfWorkMiddleware  = new UnitOfWorkMiddleware($oDatabase);
    
            // create the command bus
    
            $oCommandBus = new CommandBus([
                        $oExceptionMiddleware,
                        $oEventMiddleware,
                        $oLockingMiddleware,
                        $oUnitOfWorkMiddleware,
                        $oValdiationMiddleware,
                        $oCommandMiddleware
            ]);
            
            return $oCommandBus;
            
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
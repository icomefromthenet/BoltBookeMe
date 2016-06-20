<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
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

use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidatePropMiddleware;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ExceptionWrapperMiddleware;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\UnitOfWorkMiddleware;



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
      
       $this['commandBus'] = $app->share(function($c){
                
           # fetch database
           $oDatabase = $app['db'];
           
            $aLocatorMap = [
                //CalAddYearCommand::class            => 'handlers.cal.addyear',
              
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
                	 # fetch event dispatcher
                    $oEvent = $app['event'];
                	$oEvent->handle($oEvent);
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
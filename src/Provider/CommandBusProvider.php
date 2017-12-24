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

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Handler\RegisterMemberHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterTeamCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Handler\RegisterTeamHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\AssignTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Handler\AssignTeamMemberHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\WithdrawlTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Handler\WithdrawlTeamMemberHandler;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\StartScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\StartScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\StopScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\StopScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\ResumeScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\ResumeScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\ToggleScheduleCarryCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\ToggleScheduleCarryHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\RefreshScheduleHandler;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\AssignRuleToScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\RemoveRuleFromScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\RolloverRulesCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Handler\AssignRuleToScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Handler\RemoveRuleFromScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Handler\CreateRuleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Handler\RolloverRulesHandler;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\ClearBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Handler\ClearBookingHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\LookBookingConflictsCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Handler\LookBookingConflictsHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\TakeBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Handler\TakeBookingHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\ManualBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\WebBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Decorator\MaxBookingsDecorator;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Decorator\LeadTimeDecorator;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\CreateCustomerCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Handler\CreateCustomerHandler;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\ChangeCustomerCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Handler\ChangeCustomerHandler;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\AssignApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CancelApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CompleteApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\MoveApptWaitingCommand;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler\CreateApptHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler\CancelApptHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler\AssignApptHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler\CompleteApptHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Handler\MoveApptWaitingHandler;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Decorator\ApptNumDecorator;


use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;


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
      
        $app['bm.model.member.handler.addmember'] = $app->share(function(Application $container) use ($aConfig){
            return new RegisterMemberHandler($aConfig['tablenames'],$container['db']);
        });
      
        $app['bm.model.member.handler.addteam'] = $app->share(function(Application $container) use ($aConfig){
            return new RegisterTeamHandler($aConfig['tablenames'],$container['db']);
        });
        
        $app['bm.model.member.handler.assign'] = $app->share(function(Application $container) use ($aConfig){
            return new AssignTeamMemberHandler($aConfig['tablenames'],$container['db']);
        });
        
        $app['bm.model.member.handler.withdrawl'] = $app->share(function(Application $container) use ($aConfig){
            return new WithdrawlTeamMemberHandler($aConfig['tablenames'],$container['db']);
        });
        
        $app['bm.model.schedule.handler.start'] = $app->share(function(Application $container) use ($aConfig){
            return new StartScheduleHandler($aConfig['tablenames'],$container['db']);
        });
        
        $app['bm.model.schedule.handler.stop'] = $app->share(function(Application $container) use ($aConfig){
            return new StopScheduleHandler($aConfig['tablenames'],$container['db']);
        });
      
        $app['bm.model.schedule.handler.resume'] = $app->share(function(Application $container) use ($aConfig){
            return new ResumeScheduleHandler($aConfig['tablenames'],$container['db']);
        });
        
        $app['bm.model.schedule.handler.toggle'] = function(Application $container) use ($aConfig){
            return new ToggleScheduleCarryHandler($aConfig['tablenames'],$container['db']);
        };
        
        $app['bm.model.schedule.handler.refresh'] = function(Application $container) use ($aConfig){
            return new RefreshScheduleHandler($aConfig['tablenames'],$container['db']);
        };
        
        
        $app['bm.model.rule.handler.create'] = function(Application $container) use ($aConfig){
            return new CreateRuleHandler($aConfig['tablenames'],$container['db'], $container['bm.cronToQuery']);
        };
        
        $app['bm.model.rule.handler.addschedule'] = function(Application $container) use ($aConfig){
            return new AssignRuleToScheduleHandler($aConfig['tablenames'],$container['db']);
        };
        
        $app['bm.model.rule.handler.removeschedule'] = function(Application $container) use ($aConfig){
            return new RemoveRuleFromScheduleHandler($aConfig['tablenames'],$container['db']);
        };
        
        $app['bm.model.rule.handler.rollover'] = function(Application $container) use ($aConfig){
            return new RolloverRulesHandler($aConfig['tablenames'],$container['db'], $container['bm.cronToQuery']);
        };
        
        $app['bm.model.booking.handler.take'] = function(Application $container) use ($aConfig){
            return new TakeBookingHandler($aConfig['tablenames'],$container['db']);
        };
    
        $app['bm.model.booking.handler.web'] = function(Application $container) use ($aConfig){
            
            return new MaxBookingsDecorator(
                        new LeadTimeDecorator($container['bm.model.booking.handler.take'],$aConfig['tablenames'],$container['db'])
                        ,$aConfig['tablenames']
                        ,$container['db']);
        };
    
    
        $app['bm.model.booking.handler.manual'] = function(Application $container) use ($aConfig){
            return $container['bm.model.booking.handler.take'];
        };
        
        
        $app['bm.model.booking.handler.clear'] = function(Application $container) use ($aConfig){
            return new ClearBookingHandler($aConfig['tablenames'],$container['db']);
        };
        
        $app['bm.model.booking.handler.conflict'] = function(Application $container) use ($aConfig){
            return new LookBookingConflictsHandler($aConfig['tablenames'],$container['db']);
        };
      
      
        $app['bm.model.customer.handler.create'] = function(Application $container) use ($aConfig){
            return new CreateCustomerHandler($aConfig['tablenames'],$container['db']);
        };
        
        $app['bm.model.customer.handler.change'] = function(Application $container) use ($aConfig){
            return new ChangeCustomerHandler($aConfig['tablenames'],$container['db']);
        };
        
        $app['bm.model.appointment.handler.create'] = function(Application $container) use ($aConfig){
            return new ApptNumDecorator(new CreateApptHandler($aConfig['tablenames'],$container['db'])
                                        ,$aConfig['tablenames']
                                        ,$container['db']
                                        ,$container['bm.appnumber']);
        };
      
        $app['bm.model.appointment.handler.cancel'] = function(Application $container) use ($aConfig){
            return new CancelApptHandler($aConfig['tablenames'],$container['db']);
        };
      
        $app['bm.model.appointment.handler.assign'] = function(Application $container) use ($aConfig){
            return new AssignApptHandler($aConfig['tablenames'],$container['db']);
        };
        
        $app['bm.model.appointment.handler.complete'] = function(Application $container) use ($aConfig){
            return new CompleteApptHandler($aConfig['tablenames'],$container['db']);
        };
        
        $app['bm.model.appointment.handler.waiting'] = function(Application $container) use ($aConfig){
            return new MoveApptWaitingHandler($aConfig['tablenames'],$container['db']);
        };
        
      
        $app['bm.leagueeventhandler'] = function($c) {
            return new CustomHandler($c['dispatcher']);
        };
        
        $app['bm.commandBus.locator'] = $app->share(function(Application $c){
            
               $aLocatorMap = [
                    CalAddYearCommand::class            => 'bm.model.setup.handler.addyear',
                    SlotAddCommand::class               => 'bm.model.setup.handler.addslot',
                    SlotToggleStatusCommand::class      => 'bm.model.setup.handler.toggleslot',
                    RolloverTimeslotCommand::class      => 'bm.model.setup.handler.rolloverslot',
                    RegisterMemberCommand::class        => 'bm.model.member.handler.addmember',
                    RegisterTeamCommand::class          => 'bm.model.member.handler.addteam',
                    AssignTeamMemberCommand::class      => 'bm.model.member.handler.assign',
                    WithdrawlTeamMemberCommand::class   => 'bm.model.member.handler.withdrawl',
                    StartScheduleCommand::class         => 'bm.model.schedule.handler.start',
                    StopScheduleCommand::class          => 'bm.model.schedule.handler.stop',
                    ResumeScheduleCommand::class        => 'bm.model.schedule.handler.resume',
                    ToggleScheduleCarryCommand::class   => 'bm.model.schedule.handler.toggle',
                    RefreshScheduleCommand::class       => 'bm.model.schedule.handler.refresh',
                    AssignRuleToScheduleCommand::class  => 'bm.model.rule.handler.addschedule',
                    CreateRuleCommand::class            => 'bm.model.rule.handler.create',
                    RemoveRuleFromScheduleCommand::class=> 'bm.model.rule.handler.removeschedule',
                    RolloverRulesCommand::class         => 'bm.model.rule.handler.rollover',
                    TakeBookingCommand::class           => 'bm.model.booking.handler.take',
                    WebBookingCommand::class            => 'bm.model.booking.handler.web',
                    ManualBookingCommand::class         => 'bm.model.booking.handler.manual',
                    ClearBookingCommand::class          => 'bm.model.booking.handler.clear',
                    LookBookingConflictsCommand::class  => 'bm.model.booking.handler.conflict',
                    CreateCustomerCommand::class        => 'bm.model.customer.handler.create',
                    ChangeCustomerCommand::class        => 'bm.model.customer.handler.change',
                    CreateApptCommand::class            => 'bm.model.appointment.handler.create',
                    CancelApptCommand::class            => 'bm.model.appointment.handler.cancel',
                    AssignApptCommand::class            => 'bm.model.appointment.handler.assign',
                    CompleteApptCommand::class          => 'bm.model.appointment.handler.complete',
                    MoveApptWaitingCommand::class       => 'bm.model.appointment.handler.waiting',
                ];
            
            
             return new PimpleLocator($c, $aLocatorMap); 
            
        });
      
      
       $app['bm.commandBus'] = $app->share(function(Application $c) {
                
           # fetch database
           $oDatabase = $c['db'];
          
           // Create the Middleware that loads the commands
         
            $oCommandNamingExtractor = new ClassNameExtractor();
            $oCommandLoadingLocator  = $c['bm.commandBus.locator'];
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
                        //$oUnitOfWorkMiddleware,
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
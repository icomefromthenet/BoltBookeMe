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

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\AssignRuleToScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\RemoveRuleFromScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\RolloverRulesCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Handler\AssignRuleToScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Handler\RemoveRuleFromScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Handler\CreateRuleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Handler\RolloverRulesHandler;

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
        
        $app['bm.model.rule.handler.create'] = function(Application $container) use ($aConfig,$app){
            return new CreateRuleHandler($aConfig['tablenames'],$container['db'], $app['bm.cronToQuery']);
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
                RegisterMemberCommand::class        => 'bm.model.member.handler.addmember',
                RegisterTeamCommand::class          => 'bm.model.member.handler.addteam',
                AssignTeamMemberCommand::class      => 'bm.model.member.handler.assign',
                WithdrawlTeamMemberCommand::class   => 'bm.model.member.handler.withdrawl',
                StartScheduleCommand::class         => 'bm.model.schedule.handler.start',
                StopScheduleCommand::class          => 'bm.model.schedule.handler.stop',
                ResumeScheduleCommand::class        => 'bm.model.schedule.handler.resume',
                ToggleScheduleCarryCommand::class   => 'bm.model.schedule.handler.toggle',
                AssignRuleToScheduleCommand::class  => 'bm.model.rule.handler.addschedule',
                CreateRuleCommand::class            => 'bm.model.rule.handler.create',
                RemoveRuleFromScheduleCommand::class=> 'bm.model.rule.handler.removeschedule',
                RolloverTimeslotCommand::class      => 'bm.model.rule.handler.rollover',
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
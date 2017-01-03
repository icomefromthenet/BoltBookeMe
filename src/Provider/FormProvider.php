<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Field\CalendarYearField;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Field\ActiveTimeslotField;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Field\RuleTypeField;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Field\ScheduleTeamField;

/**
 * Loads this apps forms.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class FormProvider implements ServiceProviderInterface
{
    /**
     * @var config
     */ 
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
        
          // Extensions
        
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
          
            $oCalYearRepo  = $app['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearEntity');     
            $oTimeSlotRepo = $app['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotEntity'); 
            $oRuleTypeRepo = $app['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeEntity'); 
            $oTeamTypeRepo = $app['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamEntity'); 
          
            $types[CalendarYearField::class]    = new CalendarYearField($oCalYearRepo);
            $types[ActiveTimeslotField::class]  = new ActiveTimeslotField($oTimeSlotRepo);
            $types[RuleTypeField::class]        = new RuleTypeField($oRuleTypeRepo);
            $types[ScheduleTeamField::class]    = new ScheduleTeamField($oTeamTypeRepo);
           
            return $types;
        }));
    
        $app['form.type.extensions'] = $app->share($app->extend('form.type.extensions', function ($extensions) use ($app) {
            $extensions = [
                
            ];
        
            return $extensions;
        }));
    
    
 
        //----------------------------------------------------------------------
        # Rules Form
        
        $app['bm.form.rule.builder'] = $app->share(function () use ($app) {
   
            return $app['form.factory']
                    ->createBuilder('form',[])
                    ->setMethod('GET')
                    ->add('iCalYear',CalendarYearField::class, ['label' => 'Calendar Year:'])
                    ->add('iTimeslotId',ActiveTimeslotField::class, ['label' => 'Timeslots:'])
                    ->add('iRuleTypeId',RuleTypeField::class, ['label' => 'Rule Type:']);
        });
      
        $app['bm.form.rule.view'] = function () use ($app) {
            return $app['bm.form.rule.builder']->getForm()->createView();
        };
        
        //----------------------------------------------------------------------
        # Members Form
        
        $app['bm.form.member.builder'] = $app->share(function () use ($app) {
   
            return $app['form.factory']
                    ->createBuilder('form',[])
                    ->setMethod('GET')
                    ->add('iCalYear'        ,CalendarYearField::class,  ['label' => 'Schedule in Calendar Year'])
                    ->add('iCreatedYear'    ,CalendarYearField::class,  ['label' => 'Created in Calendar Year', 'required' => false ,'placeholder' => 'Select a Year', 'empty_data' => null ])
                    ->add('oCreatedAfter'   ,DateType::class,           ['label' => 'Created After', 'required' => false ,'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day']])   
                    ->add('oCreatedBefore'  ,DateType::class,           ['label' => 'Created Before','required' => false ,'placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day']])     
                    ->add('iScheduleTeam'   ,ScheduleTeamField::class,  ['label' => 'Belongs in Team','required'=> false]);
            
        });
      
        $app['bm.form.member.view'] = function () use ($app) {
            return $app['bm.form.member.builder']->getForm()->createView();
        };
        
        //----------------------------------------------------------------------
        # Members Details Form
        
        $app['bm.form.memberdetails.builder'] = $app->share(function () use ($app) {
   
            return $app['form.factory']
                    ->createBuilder('form',[])
                    ->setMethod('GET')
                    ->add('iMembershipId',HiddenType::class,[]);
            
            
        });
      
        $app['bm.form.memberdetails.view'] = function () use ($app) {
            return $app['bm.form.member.builder']->getForm()->createView();
        };
        
        //----------------------------------------------------------------------
        # Customer Form
        
        $app['bm.form.customer.builder'] = $app->share(function () use ($app) {
   
              return $app['form.factory']->createBuilder('form',[])
                    ->add('task', 'text', ['group' => 'Basic'])
                    ->add('dueDate', 'text', ['group' => 'Basic']);
            
        });
      
        $app['bm.form.customer.view'] = function () use ($app) {
            return $app['bm.form.customer.builder']->getForm()->createView();
        };
        
        //----------------------------------------------------------------------
        # Appointment Form
        
        $app['bm.form.appt.builder'] = $app->share(function () use ($app) {
   
              return $app['form.factory']->createBuilder('form',[])
                    ->add('task', 'text', ['group' => 'Basic'])
                    ->add('dueDate', 'text', ['group' => 'Basic']);
            
        });
      
        $app['bm.form.appt.view'] = function () use ($app) {
            return $app['bm.form.appt.builder']->getForm()->createView();
        };
        
        //----------------------------------------------------------------------
        # Schedule Form
        
        $app['bm.form.schedule.builder'] = $app->share(function () use ($app) {
   
              return $app['form.factory']->createBuilder('form',[])
                    ->add('task', 'text', ['group' => 'Basic'])
                    ->add('dueDate', 'text', ['group' => 'Basic']);
            
        });
      
        $app['bm.form.schedule.view'] = function () use ($app) {
            return $app['bm.form.schedule.builder']->getForm()->createView();
        };
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        
          
        
    }
}
/* End of Service Provider */
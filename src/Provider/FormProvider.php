<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\GroupTypeExtension;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


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
           
            return $types;
        }));
    
        $app['form.type.extensions'] = $app->share($app->extend('form.type.extensions', function ($extensions) use ($app) {
            $extensions[] = new GroupTypeExtension();
        
            return $extensions;
        }));
    
    
 
        //----------------------------------------------------------------------
        # Rules Form
        
        $app['bm.form.rule.builder'] = $app->share(function () use ($app) {
   
            $aRuleRepeatChoice = [
                'choices'  => [
                    'Repeat Rules'     => 'r',
                    'Single Day Rules' => 's',
                    'Both'             => null,
                ],    
                ['group' => 'Basic']
                
            ];
   
   
   
              return $app['form.factory']->createBuilder('form',[])
                    ->add('sRuleRepeatType', ChoiceType::class, $aRuleRepeatChoice)
                    ->add('taskb', 'text', ['group' => 'Basic'])
                    ->add('taskc', 'text', ['group' => 'Basic'])
                    ->add('taskd', 'text', ['group' => 'Basic'])
                    ->add('dueDate', 'text', ['group' => 'Basic']);
            
        });
      
        $app['bm.form.rule.view'] = function () use ($app) {
            return new \Bolt\Extension\IComeFromTheNet\BookMe\Form\FormGroupView($app['bm.form.rule.builder']->getForm(),'Basic',['Baisc']);
        };
        
        //----------------------------------------------------------------------
        # Members Form
        
        $app['bm.form.member.builder'] = $app->share(function () use ($app) {
   
              return $app['form.factory']->createBuilder('form',[])
                    ->add('task', 'text', ['group' => 'Basic'])
                    ->add('dueDate', 'text', ['group' => 'Basic']);
            
        });
      
        $app['bm.form.member.view'] = function () use ($app) {
            return new \Bolt\Extension\IComeFromTheNet\BookMe\Form\FormGroupView($app['bm.form.member.builder']->getForm(),'Basic',['Baisc']);
        };
        
        //----------------------------------------------------------------------
        # Customer Form
        
        $app['bm.form.customer.builder'] = $app->share(function () use ($app) {
   
              return $app['form.factory']->createBuilder('form',[])
                    ->add('task', 'text', ['group' => 'Basic'])
                    ->add('dueDate', 'text', ['group' => 'Basic']);
            
        });
      
        $app['bm.form.customer.view'] = function () use ($app) {
            return new \Bolt\Extension\IComeFromTheNet\BookMe\Form\FormGroupView($app['bm.form.customer.builder']->getForm(),'Basic',['Baisc']);
        };
        
        //----------------------------------------------------------------------
        # Appointment Form
        
        $app['bm.form.appt.builder'] = $app->share(function () use ($app) {
   
              return $app['form.factory']->createBuilder('form',[])
                    ->add('task', 'text', ['group' => 'Basic'])
                    ->add('dueDate', 'text', ['group' => 'Basic']);
            
        });
      
        $app['bm.form.appt.view'] = function () use ($app) {
            return new \Bolt\Extension\IComeFromTheNet\BookMe\Form\FormGroupView($app['bm.form.appt.builder']->getForm(),'Basic',['Baisc']);
        };
        
        //----------------------------------------------------------------------
        # Schedule Form
        
        $app['bm.form.schedule.builder'] = $app->share(function () use ($app) {
   
              return $app['form.factory']->createBuilder('form',[])
                    ->add('task', 'text', ['group' => 'Basic'])
                    ->add('dueDate', 'text', ['group' => 'Basic']);
            
        });
      
        $app['bm.form.schedule.view'] = function () use ($app) {
            return new \Bolt\Extension\IComeFromTheNet\BookMe\Form\FormGroupView($app['bm.form.schedule.builder']->getForm(),'Basic',['Baisc']);
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
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Field\CalendarYearField;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model\Field\YasumiLocaleField;


/**
 * Loads this HolidayRule Bundle.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class HolidayRuleFormProvider implements ServiceProviderInterface
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
     
         //----------------------------------------------------------------------
        # Rules Form
        
        $app['bm.holidayrule.choicelist'] = [
                'Australia','Austria','Belgium','Brazil','Croatia','Czechia','Denmark','Finland',
                'France','Germany','Greece','Hungary','Ireland','Italy','Japan','Netherlands','New Zealand',
                'Norway','Poland','Portugal','Romania','Slovakia','South Africa','Spain', 'Sweden',
                'Switzerland','United States','Ukraine','United Kingdom'
        ];
        
        $app['bm.form.holidayrule.builder'] = $app->share(function () use ($app) {
   
            return $app['form.factory']
                    ->createBuilder('form',[])
                    ->setMethod('GET')
                    ->add('iCalYear'        ,CalendarYearField::class, ['label' => 'Calendar Year:'])
                    ->add('sHolidayProvider',Type\ChoiceType::class, ['label' => 'Supported Countries:', 'choices' => $app['bm.holidayrule.choicelist'] ]);
                    
        });
      
        $app['bm.form.holidayrule.view'] = function () use ($app) {
            return $app['bm.form.holidayrule.builder']->getForm()->createView();
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
<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Rollover\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Loads this Queue Bunles Search Forms.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class RolloverFormProvider implements ServiceProviderInterface
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
     
        
       
        
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        
          
        
    }
}
/* End of Service Provider */
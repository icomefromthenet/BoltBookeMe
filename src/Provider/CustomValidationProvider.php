<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Valitron\Validator;



/**
 * Bootstrap any custom validation methods
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class CustomValidationProvider implements ServiceProviderInterface
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
      
        
            
       
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        
        Validator::addRule('calendarSameYear', function($field, $value, array $params, array $fields) {
                 
                 $vtime = ($value instanceof \DateTime) ? $value->format('Y') : date('Y',$value);
                 $ptime = ($fields[$params[0]] instanceof \DateTime) ? $fields[$params[0]]->format('Y') : date('Y',$fields[$params[0]]);
                 
                 return $vtime == $ptime;
                
            }, 'Calendar Year do not match');
            
            
          Validator::addRule('alphaNumAndSpace', function($field, $value, array $params, array $fields) {
              
               return \preg_match('/^([a-z0-9 ])+$/i', $value);
              
          },'Must contain a-z or 0-9 or space character');
          
        
    }
}
/* End of Service Provider */
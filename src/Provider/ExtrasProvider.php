<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentNumberGenerator;

/**
 * Bootstrap any extras that don't fit else ware
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class ExtrasProvider implements ServiceProviderInterface
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
        
        $app['bm.appnumber'] = function($c) use ($aConfig) {
            
            $sPrefix         = $aConfig['apptnumber']['prefix'];
            $sSuffix         = $aConfig['apptnumber']['suffix'];
            $iStartingIndex  = $aConfig['apptnumber']['starting'];
            
            return new AppointmentNumberGenerator($sPrefix,$sSuffix,$iStartingIndex);
            
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
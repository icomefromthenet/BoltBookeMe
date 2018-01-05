<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\CustomVoucherGenerator;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\CustomVoucherContainer;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\VoucherNumbers;

/**
 * Bootstrap The Order Generator 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class OrderServiceProvider implements ServiceProviderInterface
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
        
          
        
    }
}
/* End of Service Provider */
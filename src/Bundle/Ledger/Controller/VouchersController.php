<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\Controller;

use DateTime;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bolt\Storage\Database\Connection;
use Bolt\Extension\IComeFromTheNet\BookMe\Controller\CommonController;


/**
 * Unknown
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class VouchersController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

       
    
        
        return $oCtr;
    }
    
   

   
}
/* End of Class */

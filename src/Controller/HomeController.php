<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Controller;

use DateTime;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bolt\Storage\Database\Connection;



/**
 * The Portal Page for extension
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class HomeController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->get('',[$this,'onHomeGet'])
              ->bind('bookme-home');
   
   
      
    
        
        return $oCtr;
    }

    /**
     * Load the backend Calendar Config Page.
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onHomeGet(Application $app, Request $request)
    {
       
        $oDatabase = $this->getDatabaseAdapter();
        $oNow      = $this->getNow();
        
        
        $oMenuBuilder  = $this->getMenu('home');
        
        
        $this->bindMenuParameters($oMenuBuilder,[]);
        
        return $app['twig']->render('@BookMe/home.twig', ['title' => 'BookMe Home','menubuilder'=> $oMenuBuilder], []);
    }

   
}
/* End of Calendar Admin Controller */

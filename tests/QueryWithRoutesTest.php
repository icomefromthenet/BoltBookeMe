<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ActionRoute;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQueryWithRoutes;




class QueryWithRoutesTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
      return;
   }  
   
   
   
   
   public function testRouteGenerate()
   {
       $oDatabase = $this->getDatabaseAdapter();
       
       $oRoute = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\ActionRoute')
                      ->setConstructorArgs(['test-route-a'])
                      ->setMethods(['getUrl'])
                      ->getMock();
       
       $oUrlGenerator = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')
                             ->setMethods('generate')
                             ->getMock();
       
       $oQuery =  $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQueryWithRoutes')
                      ->setConstructorArgs([new QueryBuilder($oDatabase),'a',$oUrlGenerator])
                      ->setMethods(['doRowMappingComplete'])
                      ->getMock();
       
   }
   
}
/* End of class */
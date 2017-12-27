<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Datatable;

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
       $aData     = [];
       $sLink     = 'http//www.google.com';
       $sRel      = 'test-route-a';
       
       $oRoute = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\ActionRoute')
                      ->setConstructorArgs([$sRel])
                      ->setMethods(['getUrl'])
                      ->getMock();
       
       $oUrlGenerator = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')
                             ->setMethods(['generate','setContext','getContext'])
                             ->getMock();
       
       $oQuery =  new SelectQueryWithRoutes(new QueryBuilder($oDatabase),'a',$oUrlGenerator); 
                      
                      
       $oQuery->addActionRoute($oRoute);
      
      
       $oRoute->expects($this->once())
                    ->method('getUrl')
                    ->with($this->equalTo($oUrlGenerator),$this->isType('array'))
                    ->will($this->returnValue($sLink));   
      
      
      $aData = $oQuery->onRowMappingComplete($aData);
       
      $this->assertTrue(isset($aData['links']));
      $this->assertEquals($sRel,$aData['links'][0]['rel']);
      $this->assertEquals($sLink,$aData['links'][0]['link']);
      
      
      
   }
   
   
   public function testHasAndRemoveRouteMethods()
   {
      $oDatabase = $this->getDatabaseAdapter();
      $aData     = [];
      $sLink     = 'http//www.google.com';
      $sRel      = 'test-route-a';
      
      $oRoute = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\ActionRoute')
                   ->setConstructorArgs([$sRel])
                   ->setMethods(['getUrl'])
                   ->getMock();
      
      $oUrlGenerator = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')
                          ->setMethods(['generate','setContext','getContext'])
                          ->getMock();
      
      $oQuery =  new SelectQueryWithRoutes(new QueryBuilder($oDatabase),'a',$oUrlGenerator); 
                   
                   
      $oQuery->addActionRoute($oRoute);
      
      $this->assertTrue($oQuery->hasActionRoute($sRel));
      
      $oQuery->removeActionRoute($sRel);
      
      $this->assertFalse($oQuery->hasActionRoute($sRel));
      
      
   }
   
}
/* End of class */
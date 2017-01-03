<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuGroup;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuException;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuVisitorInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\UrlVisitor;


class MenuTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
      return;
   }  
   
   
    
    public function testMenuItem()
    {
        # Test Properties
        $sMenuName    = 'A Menu Text';
        $sSubText     = 'A Sub Text';
        $sRouteName   = 'bookme-home';
        $sIconName    = 'NameOne.png';
        $iOrder       = 100;
        $aQueryParams = ['a' =>'b'];
        $sFullUrl     = 'http://wwww.fullurl.com.au';
        
        $oItem = new MenuItem($sMenuName, $sSubText, $sRouteName, $sIconName, $iOrder, $aQueryParams);
     
        $oItem->setFullUrl($sFullUrl);
      
        $this->assertEquals($sMenuName,$oItem->getMenuItemName());
        $this->assertEquals($sSubText, $oItem->getSubText());
        $this->assertEquals($sRouteName, $oItem->getRouteName());
        $this->assertEquals($sIconName, $oItem->getIconName());
        $this->assertEquals($iOrder, $oItem->getOrder());
        $this->assertEquals($aQueryParams, $oItem->getQueryParams()->all());
        $this->assertEquals($sFullUrl, $oItem->getFullUrl());
       
        # Test Validation Data
        
        $aData = $oItem->getData();
       
        $this->assertEquals($sMenuName,$aData['item_name']);
        $this->assertEquals($sSubText,$aData['item_subtext']);
        $this->assertEquals($sRouteName,$aData['item_route']);
        $this->assertEquals($sIconName,$aData['item_icon']);
        $this->assertEquals($iOrder,$aData['item_order']);
        $this->assertEquals($aQueryParams,$aData['item_params']);
       
        
        # Test Validation Rules
        $aRules = $oItem->getRules();
        
        $this->assertNotEmpty($aRules);
        
        
        # Verify Sucessful Validation
        $this->assertTrue($oItem->validate());
        
        # Verify a failed validation
        $oItem = new MenuItem($sMenuName, $sSubText, $sRouteName, $sIconName, -1, $aQueryParams);
        
        try {
           $oItem->validate();
           $this->assertFalse(true,'Menu item should have failed validation');
        }
        catch(MenuException $e) {
          $this->assertEquals('Validation has failed for menu Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem::'.$sMenuName,$e->getMessage());
          $this->assertEquals($oItem,$e->getMenuItem());
          $this->assertNotEmpty($e->getValidationFailures());
        }
       
    }
   
    public function testMenuGroup()
    {
        # Test Properties
        $sGroupName = 'A Group';
        $iOrder     = 200;
        

        $oItem = new MenuGroup($sGroupName, $iOrder);
    
       
        $this->assertEquals($sGroupName,$oItem->getGroupName());
        $this->assertEquals($iOrder, $oItem->getOrder());
      
       
        # Test Validation Data
        
        $aData = $oItem->getData();
        
        $this->assertEquals($sGroupName,$aData['group_name']);
        $this->assertEquals($iOrder,$aData['group_order']);
        
        
        $aRules = $oItem->getRules();
        
        $this->assertNotEmpty($aRules);
        
        
        # Verify Sucessful Validation
        $this->assertTrue($oItem->validate());
      
      
        # Verify a failed validation
        $oItem = new MenuGroup($sGroupName, -1);
        
        try {
           $oItem->validate();
           $this->assertFalse(true,'Menu group should have failed validation');
        }
        catch(MenuException $e) {
          $this->assertEquals('Validation has failed for menu Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuGroup::'.$sGroupName,$e->getMessage());
          $this->assertEquals($oItem,$e->getMenuItem());
          $this->assertNotEmpty($e->getValidationFailures());
        }
        
        # test add group and order sorting
        $oItemA = new MenuItem('Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oItemB = new MenuItem('Menu Item B','Example Menu Item', 'bla', 'bla.png', 1, []);
        
        $oMenuGroup  = new MenuGroup($sGroupName, 1);
       
        $oMenuGroup->addMenuItem($oItemA);
        $oMenuGroup->addMenuItem($oItemB);
       
        $aMenuItemInOrder = $oMenuGroup->getMenuItems();
       
        $this->assertCount(2,$aMenuItemInOrder);
        
        $this->assertEquals(1,$aMenuItemInOrder[0]->getOrder());
        $this->assertEquals(2,$aMenuItemInOrder[1]->getOrder());
        
   }
   
   
   public function testMenuBuilderAddParam()
   {
        $oMenuGroupOne  = new MenuGroup('Group 1', 1);
        $oMenuGroupTwo  = new MenuGroup('Group 2', 2);

        $oGroupOneItemA = new MenuItem('Group One Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oGroupOneItemB = new MenuItem('Menu One Item B','Example Menu Item', 'bla', 'bla.png', 1, []);
        
        $oGroupTwoItemA = new MenuItem('Group Two Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oGroupTwoItemB = new MenuItem('Group Two Menu Item B','Example Menu Item', 'bla', 'bla.png', 1, []);
        
        $oMenuGroupOne->addMenuItem($oGroupOneItemA);
        $oMenuGroupOne->addMenuItem($oGroupOneItemB);
        
        $oMenuGroupTwo->addMenuItem($oGroupTwoItemA);
        $oMenuGroupTwo->addMenuItem($oGroupTwoItemB);
        
        $oMenuBuilder = new MenuBuilder();
        
        $oMenuBuilder->addMenuGroup($oMenuGroupOne);
        $oMenuBuilder->addMenuGroup($oMenuGroupTwo);
       
        $oMenuBuilder->addQueryParam('entity',1);
        
        $this->assertEquals(1,$oGroupOneItemA->getQueryParams()->get('entity'));
        $this->assertEquals(1,$oGroupOneItemB->getQueryParams()->get('entity'));
        $this->assertEquals(1,$oGroupTwoItemA->getQueryParams()->get('entity'));
        $this->assertEquals(1,$oGroupTwoItemB->getQueryParams()->get('entity'));
        
       
   }
   
   public function testMenuBuilderValidationSuccess()
   {
        // Test Validation
        $oMenuGroupOne  = new MenuGroup('Group 1', 1);
        $oMenuGroupTwo  = new MenuGroup('Group 2', 2);

        $oGroupOneItemA = new MenuItem('Group One Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oGroupOneItemB = new MenuItem('Menu One Item B','Example Menu Item', 'bla', 'bla.png', 1, []);
        
        $oGroupTwoItemA = new MenuItem('Group Two Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oGroupTwoItemB = new MenuItem('Group Two Menu Item B','Example Menu Item', 'bla', 'bla.png', 1, []);
        
        $oMenuGroupOne->addMenuItem($oGroupOneItemA);
        $oMenuGroupOne->addMenuItem($oGroupOneItemB);
        
        $oMenuGroupTwo->addMenuItem($oGroupTwoItemA);
        $oMenuGroupTwo->addMenuItem($oGroupTwoItemB);
        
        try{
        $oMenuBuilder = new MenuBuilder();
        
         $oMenuBuilder->addMenuGroup($oMenuGroupOne);
        $oMenuBuilder->addMenuGroup($oMenuGroupTwo);
        
        $this->assertTrue($oMenuBuilder->validate(),'Menu Builder validation routine should have passed');
       
        
        }catch(MenuException $e){
            var_dump($e->getValidationFailures());
            exit;
            $this->assertTrue(false,'Validation should of passed for menu builder in this testcase');
        }
        
   }
   
    public function testMenuBuilderValidationFailedOnGroup()
    {
    
        $oMenuGroupOne  = new MenuGroup('Group 1', 1);
        $oMenuGroupTwo  = new MenuGroup('Group 2', -1);
        
        $oGroupOneItemA = new MenuItem('Group One Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oGroupOneItemB = new MenuItem('Menu One Item B','Example Menu Item', 'bla', 'bla.png', 1, []);
        
        $oGroupTwoItemA = new MenuItem('Group Two Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oGroupTwoItemB = new MenuItem('Group Two Menu Item B','Example Menu Item', 'bla', 'bla.png', 1, []);
        
        $oMenuGroupOne->addMenuItem($oGroupOneItemA);
        $oMenuGroupOne->addMenuItem($oGroupOneItemB);
        
        $oMenuGroupTwo->addMenuItem($oGroupTwoItemA);
        $oMenuGroupTwo->addMenuItem($oGroupTwoItemB);
        
        $oMenuBuilder = new MenuBuilder();
        
        $oMenuBuilder->addMenuGroup($oMenuGroupOne);
        $oMenuBuilder->addMenuGroup($oMenuGroupTwo);
        
        
        try {
            $oMenuBuilder->validate();
            $this->assertFalse(true,'Menu group should have failed validation');
        }
        catch(MenuException $e) {
            $this->assertEquals('Validation has failed for menu Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuGroup::Group 2',$e->getMessage());
            $this->assertEquals($oMenuGroupTwo,$e->getMenuItem());
            $this->assertNotEmpty($e->getValidationFailures());
        }
    
    }
   
    public function testMenuBuilderValidationFailedOnItem()
    {
        $oMenuGroupOne  = new MenuGroup('Group 1', 1);
        $oMenuGroupTwo  = new MenuGroup('Group 2', 2);
        
        $oGroupOneItemA = new MenuItem('Group One Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oGroupOneItemB = new MenuItem('Menu One Item B','Example Menu Item', 'bla', 'bla.png', 1, []);
        
        $oGroupTwoItemA = new MenuItem('Group Two Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        $oGroupTwoItemB = new MenuItem('Group Two Menu Item B','Example Menu Item', 'bla', 'bla.png', -1, []);
        
        $oMenuGroupOne->addMenuItem($oGroupOneItemA);
        $oMenuGroupOne->addMenuItem($oGroupOneItemB);
        
        $oMenuGroupTwo->addMenuItem($oGroupTwoItemA);
        $oMenuGroupTwo->addMenuItem($oGroupTwoItemB);
        
        $oMenuBuilder = new MenuBuilder();
        
        $oMenuBuilder->addMenuGroup($oMenuGroupOne);
        $oMenuBuilder->addMenuGroup($oMenuGroupTwo);
        
        
        try {
            $oMenuBuilder->validate();
            $this->assertFalse(true,'Menu group should have failed validation');
        }
        catch(MenuException $e) {
            $this->assertEquals('Validation has failed for menu Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem::Group Two Menu Item B',$e->getMessage());
            $this->assertEquals($oGroupTwoItemB,$e->getMenuItem());
            $this->assertNotEmpty($e->getValidationFailures());
        }
    
    }
    
   
    public function testMenuGroupVisitor()
    {
        $oMenuGroupOne  = new MenuGroup('Group 1', 1);
        $oGroupOneItemA = new MenuItem('Group One Menu Item A','Example Menu Item', 'bla', 'bla.png', 2, []);
        
        $oMenuGroupOne->addMenuItem($oGroupOneItemA); 
         
        $oMockVisitor   = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuVisitorInterface')
                                ->setMethods(['visitMenuGroup','visitMenuItem'])
                                ->getMock();
                                
        
        $oMockVisitor->expects($this->once())
                     ->method('visitMenuGroup');
                     
         $oMockVisitor->expects($this->once())
                     ->method('visitMenuItem');
                     
        $oMenuGroupOne->visit($oMockVisitor);             
    }
    
    
    public function testUrlGenerator()
    {
       
        $aParams        = ['a'=> 1,'c' => 3];
        $sRoute         = 'route/{id}';
        $oGroupOneItemA = new MenuItem('Group One Menu Item A','An item of Subtext', $sRoute, 'bla.png', 2, []);
        
        $oUrlGenerator = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')
                                ->disableOriginalConstructor()
                                ->setMethods(['generate','setContext','getContext'])
                                ->getMock();
        
        $oUrlGenerator->expects($this->once())
                      ->method('generate')
                      ->with($this->equalTo($sRoute), $this->equalTo($aParams));
        
        
        $oVisitor  = new UrlVisitor($oUrlGenerator,$aParams);
        
        $oVisitor->visitMenuItem($oGroupOneItemA);
        
        
        
    }
   
    public function testUrlGeneratorWithException()
    {
        
         $aParams        = ['a'=> 1,'c' => 3];
        $sRoute         = 'route/{id}';
        $oGroupOneItemA = new MenuItem('Group One Menu Item A','An item of Subtext', $sRoute, 'bla.png', 2, []);
        
        $oUrlGenerator = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')
                                ->disableOriginalConstructor()
                                ->setMethods(['generate','setContext','getContext'])
                                ->getMock();
        
        $oUrlGenerator->expects($this->once())
                      ->method('generate')
                      ->will($this->throwException(new \Exception('aaaaaa')));
        
        
        $oVisitor  = new UrlVisitor($oUrlGenerator,$aParams);
        
        try {
            
            $oVisitor->visitMenuItem($oGroupOneItemA);
            
            $this->assertTrue(false,'Exception was not thrown when expected');
        } catch (MenuException $e) {
            
            $this->assertTrue(true);
        }
        
    }
}
/* end of file */

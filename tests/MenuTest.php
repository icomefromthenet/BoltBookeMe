<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuGroup;
use Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuException;


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
        
        $oItem = new MenuItem($sMenuName, $sSubText, $sRouteName, $sIconName, $iOrder, $aQueryParams);
     
      
        $this->assertEquals($sMenuName,$oItem->getMenuName());
        $this->assertEquals($sSubText, $oItem->getSubText());
        $this->assertEquals($sRouteName, $oItem->getRouteName());
        $this->assertEquals($sIconName, $oItem->getIconName());
        $this->assertEquals($iOrder, $oItem->getOrder());
        $this->assertEquals($aQueryParams, $oItem->getQueryParams());
       
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
          $this->assertEquals('Validation has failed for menu Bolt\Extension\IComeFromTheNet\BookMe\Menu\MenuItem',$e->getMessage());
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
        
        
        # Test Validation Rules
        
        
        
        
        # Verify Sucessful Validation
        
        
        # Verify a failed validation
       
       
   }
    
}
/* end of file */

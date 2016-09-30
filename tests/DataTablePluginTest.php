<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\FixedHeaderPlugin;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\FixedColumnPlugin;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General\AjaxOptions;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema\ColumnRenderFunc;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema\ColumnRenderOption;



class DataTablePluginTest extends ExtensionTest
{
    
    
    protected function handleEventPostFixtureRun()
    {
        return;
    }  


    public function testFixedHeaderPlugin()
    {
        $oPlugin = new FixedHeaderPlugin();
        
        $oPlugin->setHeaderMode(false);
        $oPlugin->setFooterMode(true);
        $oPlugin->setFooterOffset(100);
        $oPlugin->setHeaderOffset(200);
        
        $aStruct = $oPlugin->getStruct();
        
        $this->assertNotEmpty($aStruct);
        
        $this->assertEquals(false,$aStruct['fixedHeader']['header']);
        $this->assertEquals(true,$aStruct['fixedHeader']['footer']);
        $this->assertEquals(200,$aStruct['fixedHeader']['headerOffset']);
        $this->assertEquals(100,$aStruct['fixedHeader']['footerOffset']);
    }
    

    
    public function testFixedColumnPlugin()
    {
        
        $oPlugin = new FixedColumnPlugin();
        
        $oPlugin->setHeightCalculationAuto();
        $oPlugin->setHeightCalculationCallback('window.func');
        $oPlugin->setNumberFixedRightColumn(2);
        $oPlugin->setNumberFixedLeftColumn(0);
        
        $aStruct = $oPlugin->getStruct();
        
        $this->assertEquals(0,$aStruct['fixedColumns']['iLeftColumns']);
        $this->assertEquals(2,$aStruct['fixedColumns']['iRightColumns']);
        $this->assertEquals('window.func',$aStruct['fixedColumns']['fnDrawCallback']->getValue());
        $this->assertEquals('auto',$aStruct['fixedColumns']['sHeightMatch']);
    }
       
    
    public function testGeneralAjaxOptions()
    {
        
        $oPlugin = new AjaxOptions();
       
        $sUrl = 'https://www.icomefromthenet.com/data';
        $sMethod = 'POST';
        $sDataType = 'HTML';
        
       
        $oPlugin->setDataUrl($sUrl);
        $oPlugin->setHttpRequestMethod($sMethod);
        $oPlugin->setResponseDataType($sDataType);
        $oPlugin->setRequestParam('index1',1);
        
        $aStruct = $oPlugin->getStruct();
       
        $this->assertEquals($sUrl,$aStruct['ajax']['url']);
        $this->assertEquals($sMethod,$aStruct['ajax']['method']);
        $this->assertEquals($sDataType,$aStruct['ajax']['dataType']);
        $this->assertEquals(1,$aStruct['ajax']['data']['index1']);
    }
       
  
    public function testSchemaColumnRenderWithFunc()
    {
        
        $oPlugin = new ColumnRenderFunc('window.func');
        
        $aStruct = $oPlugin->getStruct();
       
        $this->assertEquals('window.func',$aStruct['render']);
        
    }
    
    
     public function testGeneralColumnRenderOption()
    {
        
        $oPlugin = new ColumnRenderOption();
       
        $sDefault = '-';
        $sFilter = 'column_a';
        $sDisplay = 'column_b';
        
       
        $oPlugin->setDisplayIndex($sDisplay);
        $oPlugin->setFilterIndex($sFilter);
        $oPlugin->setEmptyDefault($sDefault);
        
        $aStruct = $oPlugin->getStruct();
       
        $this->assertEquals($sDefault,$aStruct['render']['_']);
        $this->assertEquals($sDisplay,$aStruct['render']['display']);
        $this->assertEquals($sFilter,$aStruct['render']['filter']);
        
    }
}
/* end of file */

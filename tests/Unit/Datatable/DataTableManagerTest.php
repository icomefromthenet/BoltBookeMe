<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Datatable;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableException;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableEventRegistry;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\FixedHeaderPlugin;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\FixedColumnPlugin;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\ScrollerPlugin;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\SelectPlugin;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General\AjaxOptions;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General\DefaultOptions;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema\ColumnRenderFunc;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema\ColumnRenderOption;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock\TestDataTable;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\DenseFormat;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;


class DataTableManagerTest extends ExtensionTest
{
    
    
    protected function handleEventPostFixtureRun()
    {
        return;
    }  


    public function testMockDataTableConfig()
    {
        
        $mockUrl = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')->getMock();
        
        $oMockTable = new  TestDataTable(new StringOutput(new DenseFormat()),$mockUrl);
        
        # test accessors
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General\AjaxOptions',$oMockTable->getOptionSet('AjaxOptions'));
       
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General\DefaultOptions',$oMockTable->getOptionSet('DefaultOptions'));
   
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\ScrollerPlugin',$oMockTable->getPlugin('ScrollerPlugin'));
   
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema\ColumnSchema',$oMockTable->getSchema());
   
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput',$oMockTable->getOutputWriter());
        
        # test the config output expected
        $aConfig = $oMockTable->getStruct();
        
        foreach(array('ajax','scrollX','columns','scroller') as $sIndex) {
            $this->assertArrayHasKey($sIndex,$aConfig);
        }
        $sConfig = $oMockTable->writeConfig();
        $this->assertNotEmpty($sConfig);
        
        # test the event output as expected
        $aEvents = $oMockTable->getEvents();
        $this->assertCount(1,$aEvents);
        $this->assertEquals(DataTableEventRegistry::CORE_INIT,$aEvents[0][0]);
        
    }
    

    
}
/* end of file */

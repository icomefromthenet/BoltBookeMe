<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

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
        $oMockTable = new  TestDataTable(new StringOutput(new DenseFormat()));
        
        # test accessors
        
        
        # test the config output expected
        $aConfig = $oMockTable->getStruct();
        
        $sConfig = $oMockTable->writeConfig();
        
        # test the event output as expected
        
        
    }
    

    
}
/* end of file */

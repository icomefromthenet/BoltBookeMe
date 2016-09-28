<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\DenseFormat;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;




class DataTableWriterTest extends ExtensionTest
{
    
    
    protected function handleEventPostFixtureRun()
    {
        return;
    }  

    
    public function testSimpleExampleOfOutputWriter()
    {
        $oFormat = new DenseFormat();    
        $oOutput = new StringOutput($oFormat);   
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => 'bbbb'];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":"bbbb"}',$oOutput->write($aExample)->bytes());
    }
       
    public function testPrimitiveTypesOutputWriter()
    {
        $oFormat = new DenseFormat();    
        $oOutput = new StringOutput($oFormat);   
        
        // Test String
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => 'bbbb'];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":"bbbb"}',$oOutput->write($aExample)->bytes());
        
        // Test Integer
        
        $oOutput = new StringOutput($oFormat);   
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => 1];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":1}',$oOutput->write($aExample)->bytes());
        
        // Test Null
        $oOutput = new StringOutput($oFormat);   
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => null];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":null}',$oOutput->write($aExample)->bytes());
        
        // test bool
        
        $oOutput = new StringOutput($oFormat);   
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => true];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":true}',$oOutput->write($aExample)->bytes());
        
        $oOutput = new StringOutput($oFormat);   
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => false];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":false}',$oOutput->write($aExample)->bytes());
        
        // Test with float
        
        $oOutput = new StringOutput($oFormat);   
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => false];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":false}',$oOutput->write($aExample)->bytes());
        
         // Test with array
        
        $oOutput = new StringOutput($oFormat);   
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => ['a' => 1]];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":{"a":1}}',$oOutput->write($aExample)->bytes());
        
         // Test with function ref
        
        $oOutput = new StringOutput($oFormat);   
        $oFuncRef = new FunctionReferenceType('func');
        
        $aExample = ['ValueA' => 'aaaa', 'ValueB' => ['a' => $oFuncRef]];
        
        $this->assertEquals('{"ValueA":"aaaa","ValueB":{"a":func}}',$oOutput->write($aExample)->bytes());
    }
  
    
}
/* end of file */

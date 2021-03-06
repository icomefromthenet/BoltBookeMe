<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\FormContainer;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\SchemaFieldFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\FormFieldFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\SchemaFieldItem;


use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\DenseFormat;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


class JsonFormTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
      return;
   }  
   
   
   public function testJSONBuilderFactory()
    {
        $oOuput = new StringOutput( new DenseFormat());
        
        $oObjectBuilder = OptionFactory::createObjectBuilder($oOuput);
        
        $this->assertInstanceOf('Bolt\\Extension\\IComeFromTheNet\\BookMe\\Form\\JSONObjectBuilder',$oObjectBuilder);
        
        $oArrayBuilder = OptionFactory::createArrayBuilder($oOuput);
        
        $this->assertInstanceOf('Bolt\\Extension\\IComeFromTheNet\\BookMe\\Form\\JSONArrayBuilder',$oArrayBuilder);
 
    }
    
    public function testJsonObjectBuilder()
    {
        $oOuput = new StringOutput( new DenseFormat());
        
        $oBuilder = new JSONObjectBuilder($oOuput);
        
        $oBuilder->addPrimitive('a',100);
        $aStruct = $oBuilder->getStruct();
        $this->assertEquals(100,$aStruct['a']);
        
        $oRef = new FunctionReferenceType('windo.func');
        
        $oBuilder->addFuncRef('k',$oRef);
        $aStruct = $oBuilder->getStruct();
        $this->assertEquals($oRef,$aStruct['k']);
        
        $oArrayBuilder = OptionFactory::createArrayBuilder($oOuput);
        $oClass = $oBuilder->addArrayValue('b',$oArrayBuilder);
        $aStruct = $oBuilder->getStruct();
        $this->assertEquals($oBuilder,$oClass);
        $this->assertEquals([],$aStruct['b']);
        
        $oObjectBuilder = OptionFactory::createObjectBuilder($oOuput);
        $oClass = $oBuilder->addObjectValue('d',$oObjectBuilder);
        $aStruct = $oBuilder->getStruct();
        $this->assertEquals($oBuilder,$oClass);
        $this->assertInstanceOf('stdClass',$aStruct['d']);
        
        $this->assertEquals($oObjectBuilder,$oBuilder->getOption('d'));
        $this->assertEquals(true,$oBuilder->hasOption('d'));
        
        
    }
   
    public function testJsonArrayBuilder()
    {
        $oOuput = new StringOutput( new DenseFormat());
        
        $oBuilder = new JSONArrayBuilder($oOuput);
        
        $oBuilder->addPrimitive(100);
        $aStruct = $oBuilder->getStruct();
        $this->assertEquals(100,$aStruct[0]);
        
        $oRef = new FunctionReferenceType('windo.func');
        
        $oBuilder->addFuncRef($oRef);
        $aStruct = $oBuilder->getStruct();
        $this->assertEquals($oRef,$aStruct[1]);
        
        $oArrayBuilder = OptionFactory::createArrayBuilder($oOuput);
        $oClass = $oBuilder->addArrayValue($oArrayBuilder);
        $aStruct = $oBuilder->getStruct();
        $this->assertEquals($oBuilder,$oClass);
        $this->assertEquals([],$aStruct[2]);
        
        $oObjectBuilder = OptionFactory::createObjectBuilder($oOuput);
        $oClass = $oBuilder->addObjectValue($oObjectBuilder);
        $aStruct = $oBuilder->getStruct();
        $this->assertEquals($oBuilder,$oClass);
        $this->assertInstanceOf('stdClass',$aStruct[3]);
        
         $this->assertEquals($oObjectBuilder,$oBuilder->getOption(3));
        $this->assertEquals(true,$oBuilder->hasOption(3));
        
    }
    
    
    public function testFormContainer()
    {
        $oOuput = new StringOutput( new DenseFormat());
       
        $oForm = new FormContainer($oOuput);
        
        $this->assertNotEmpty($oForm->getSchema());
        $this->assertNotEmpty($oForm->getForm());
        
        $aStruct = $oForm->getStruct();
        
        $this->assertArrayHasKey('schema',$aStruct);
        $this->assertArrayHasKey('form',$aStruct);
        $this->assertArrayHasKey('type',$aStruct['schema']);
        $this->assertArrayHasKey('properties',$aStruct['schema']);
        
        
    }
    
    public function testSchemaField()
    {
        $oOuput = new StringOutput( new DenseFormat());
        
        $oForm = new FormContainer($oOuput);
         
        $oForm->getSchema()->addField('field1',SchemaFieldFactory::createIntegerField($oOuput)
                                                                 ->setTitle('myfield')    
        ); 
        
        $aStruct = $oForm->getSchema()->getField('field1')->getStruct();
        
        $this->assertArrayHasKey('title',$aStruct);
        
       
    }
    
    public function testFormField()
    {
       $oOuput = new StringOutput( new DenseFormat());
        
       $oForm = new FormContainer($oOuput);
       
       $oForm->getForm()->addField(FormFieldFactory::createEmailType($oOuput)
                                                    ->setKey('email')); 

        // Test field find by key    
        $oFieldA = $oForm->getForm()->getField(0);    
        $oFieldB = $oForm->getForm()->getFieldByName('email');
        
        $this->assertNotEmpty($oFieldA);
        $this->assertNotEmpty($oFieldB);
        $this->assertEquals($oFieldA,$oFieldB);
        
    }
    
}
/* end of file */
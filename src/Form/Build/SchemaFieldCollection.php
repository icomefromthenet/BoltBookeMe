<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form\Build;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionBuilderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;


/**
 * A collection fields inside the form schema that converted into a form field at runtime
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class SchemaFieldCollection extends JSONObjectBuilder
{
   
    
    protected function setDefaults()
    {
          $this->addPrimitive('type','object');
          $this->addObjectValue('properties',OptionFactory::createObjectBuilder($this->oOutput));
    }
    
    
    /**
     * Create a field and return the
     * 
     * @param string        $sFieldName     The field name
     * @param SchemaField   $oSchema        The field properties
     * @return self
     */ 
    public function addField($sFieldName, SchemaField $oSchemaField)
    {
        $this->getOption('properties')->addObjectValue($sFieldName, $oSchemaField);
    
        return $this;
    }
    
    
    /**
     * Fetch the field in this schema, if field has properties(children) you select
     * the parent only and call getField on next element until descend. 
     * 
     * @return JSONObjectBuilder
     */ 
    public function getField($sFieldName)
    {
        return $this->getOption('properties')->getOption($sFieldName);
    }
    
    
}
/* End of Class */
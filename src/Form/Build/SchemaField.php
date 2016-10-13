<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form\Build;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionBuilderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;


/**
 * A field inside the form schema that converted into a form field at runtime
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class SchemaField extends JSONObjectBuilder
{
   
    
    protected function setDefaults()
    {
          
    }
    
    
    //-------------------------------------------------------------
    # Field Attribtes
    
    /**
     * Set the title of this schema field, used as the lable during the 
     * form rendering 
     * 
     * @return self;
     * @param string    $sTitle
     */ 
    public function setTitle($sTitle)
    {
        return $this->addPrimitive('title', $sTitle);
    }
    
    /**
     * Sets a default value used in the field value 
     * 
     * @return self
     * @param mixed $mDefault
     */ 
    public function setDefault($mDefault)
    {
        return $this->addPrimitive('default', $mDefault);
    }
    
    /**
     * This provides a full description of the of purpose this field
     * 
     * @param string    $sDescription
     * @return self
     */ 
    public function setDescription($sDescription)
    {
        return $this->addPrimitive('description', $sDescription)
    }
    
    /**
     * Sets if this field is required , validation fail if form element not populated
     * 
     * @return self
     * @param boolean   $bIsRequired
     */ 
    public function setRequired($bIsRequired)
    {
        return $this->addPrimitive('required',$bIsRequired);
    }
    
    
    
    //-------------------------------------------------------------------------
    # String Properties
    
    
    
    
    //------------------------------------------------------------------------
    # Number and integer properties
    
    
    
    
    //-------------------------------------------------------------------------
    # Enum and items
    
    public function setEnum(JSONArrayBuilder $aValues)
    {
        return $this->addArrayValue('enum',$aValues);
    }
    
    /**
     * Adds items to this field, will convert this field into array type
     * 
     * @return self
     * @param SchemaFieldItem   $oSchemaItem
     */ 
    public function addItems(SchemaFieldItem $oSchemaItem)
    {
        if(false === $this->hasField('items')) {
            $this->addObjectValue('items',$oSchemaItem);
            $this->addPrimitive('type','array'); // must be object to have properties
        }
        
        return $this;
    }
    
    //------------------------------------------------------------------------
    # Sub Fields
    
    /**
     * Adds a new field as a property
     * 
     * @return self
     * @param   string          $sFieldName
     * @param   SchemaField     $oField
     */ 
    public function addField($sFieldName, SchemaField $oField)
    {
        if(false === $this->hasField('properties')) {
            $this->addObjectValue('properties',OptionFactory::createObjectBuilder($this->oOutput);
            $this->addPrimitive('type','object'); // must be object to have properties
        }
        
        $this->getOption('properties')->addObjectValue($sFieldName, $oField);
         
         return $this;
    }
    
    
    
}
/* End of Class */
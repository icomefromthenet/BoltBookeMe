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
        return $this->addPrimitive('description', $sDescription);
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
    
    /**
     * Provides a regular
     * expression that a string instance MUST match in order to be valid.
     * 
     * This a javascript compitable regex.
     * 
     * @return self
     * @param string    $sPattern
     */ 
    public function setRegexPattern($sPattern)
    {
        return $this->addPrimitive('pattern', $sPattern);
    }
    
    /**
     * When the instance value is a string, this defines the maximum length
     * of the string.
     * 
     * @return self
     * @param integer $iMaxLength
     */ 
    public function setMaxLength($iMaxLength)
    {
        return $this->addPrimitive('maxLength', $iMaxLength);
    }
    
    /**
     * When the instance value is a string, this defines the minimum length
     * of the string.
     * 
     * @return self
     * @param integer $iMinLength
     */ 
    public function setMinLength($iMinLength)
    {
        return $this->addPrimitive('minLength', $iMinLength);
    }
    
    
    
    //------------------------------------------------------------------------
    # Number and integer properties
    
    /**
     * This attribute defines the minimum value of the instance property
     * when the type of the instance value is a number.
     * 
     * @return self;
     * @param mixed $mMinimum   A Number
     */ 
    public function setMinimum($mMinimum)
    {
        return $this->addPrimitive('minimum',$mMinimum);
    }
    
    /**
     * This attribute defines the maximum value of the instance property
     * when the type of the instance value is a number.
     *
     * @return self;
     * @param mixed $mMaximum   A Number
     */ 
    public function setMaximum($mMaximum)
    {
        return $this->addPrimitive('maximum', $mMaximum);
    }
    
    /**
     * Indicates if the value of the instance can not equal the number defined by the
     * "minimum" attribute.  
     * 
     * @return self
     * @param boolean $bExclusive   If it should
     */ 
    public function setExclusiveMinimum($bExclusive)
    {
        return $this->addPrimitive('exclusiveMinimum', $bExclusive);  
    }
    
    /**
     * indicates if the value of the instance can not equal the number defined by the
     * "maximum" attribute.  This is false by default, meaning the instance
     * value can be less then or equal to the maximum value.
     * 
     * @return self
     * @param boolean   $bExclusive
     */ 
    public function setExclusiveMaximum($bExclusive)
    {
        return $this->addPrimitive('exclusiveMaximum', $bExclusive);
    }
    
    
    
    
    //-------------------------------------------------------------------------
    # Enum 
    
    public function setEnum(JSONArrayBuilder $aValues)
    {
        return $this->addArrayValue('enum',$aValues);
    }
    
    //--------------------------------------------------------------------------
    # Array Fields
    
    /**
     * This attribute defines the minimum number of values in an array when
     * the array is the instance value.
     * 
     * @return self
     * @param integer   $iMin
     */ 
    public function setMinItems($iMin)
    {
        return $this->addPrimitive('minItems', $iMin);
    }
    
    /**
     * This attribute defines the maximum number of values in an array when
     * the array is the instance value.
     * 
     * @return self
     * @param integer   $iMax
     */ 
    public function maxItems($iMax)
    {
        return $this->addPrimitive('maxItems', $iMax);
    }
    
    /**
     * This attribute indicates that all items in an array instance MUST be
     * unique (contains no two identical values).
     * 
     * @return self
     * @param boolean   $bUnique
     */ 
    public function uniqueItems($bUnique)
    {
        return $this->addPrimitive('uniqueItems', $bUnique);
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
            $this->addObjectValue('properties',OptionFactory::createObjectBuilder($this->oOutput));
            $this->addPrimitive('type','object'); // must be object to have properties
        }
        
        $this->getOption('properties')->addObjectValue($sFieldName, $oField);
         
         return $this;
    }
    
    
    
}
/* End of Class */